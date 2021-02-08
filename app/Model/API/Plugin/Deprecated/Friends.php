<?php


namespace App\Model\API\Plugin\Deprecated;

use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class Friends
 * @deprecated
 * @package App\Model\API\Plugin
 */
class Friends
{
    private Explorer $Explorer;
    private Cache $cache;

    /**
     * Friends constructor.
     * @param Explorer $Explorer
     * @param Storage $storage
     * database.friends
     */
    public function __construct(Explorer $Explorer, Storage $storage)
    {
        $this->cache = new Cache($storage);
        $this->Explorer = $Explorer;
    }

    /**
     * Používá se i jako dodavatel UUID, MojangAPI nedává správné.
     *
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function getRowByName($username) {
           return $this->Explorer->table('fr_players')->where('player_name = ?', $username)->fetch();
    }

    /**
     * @param $username
     * @return array
     */
    public function getFriends($username) {
            $friendsSql = $this->Explorer->query('select friend.player_name as friend_name, friend.player_id as friend_id, 
                                    friend.last_online as friend_last_online, player.player_name as player_name, player.player_id as player_id,
                                    player.last_online as player_last_online
                                    from fr_friend_assignment
                                    inner join fr_players as player on fr_friend_assignment.friend1_id = player.player_id
                                    inner join fr_players as friend on fr_friend_assignment.friend2_id = friend.player_id
                                     WHERE player.player_name = ? OR friend.player_name = ?', $username, $username)->fetchAll();
            $friendsArr = [];
            // oboustranně fungujicí
            foreach ($friendsSql as $friend) {
                $friendObject = new \stdClass();
                if ($friend->friend_name !== $username) {
                    $friendObject->player_name = $friend->friend_name;
                    $friendObject->player_id = $friend->friend_id;
                    $friendObject->lastOnline = $friend->friend_last_online;
                } else {
                    $friendObject->player_name = $friend->player_name;
                    $friendObject->player_id = $friend->player_id;
                    $friendObject->lastOnline = $friend->player_last_online;
                }

                array_push($friendsArr, $friendObject);
            }

            return $friendsArr;
    }

    /**
     * @param $player
     * @param int $friendId
     * @return int
     */
    public function removeFriend($player, int $friendId) {
        $playerId = $this->getRowByName($player)->player_id;
        return $this->Explorer->table('fr_friend_assignment')->where('friend1_id = ? AND friend2_id = ? OR friend2_id = ? AND friend1_id=?',
            $playerId, $friendId, $playerId, $friendId)->delete();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function countOfFriends($username)
    {
        if (is_null($this->cache->load('countOfFriends_' . $username))) {
            $this->cache->save('countOfFriends_' . $username, $this->Explorer->query("select COUNT('*')
                                    from fr_friend_assignment
                                    inner join fr_players as player on fr_friend_assignment.friend1_id = player.player_id
                                    inner join fr_players as friend on fr_friend_assignment.friend2_id = friend.player_id
                                     WHERE player.player_name = ? OR friend.player_name = ?", $username, $username)->getRowCount(), [
                Cache::EXPIRE => '15 minutes'
            ]);
        }

        return $this->cache->load('countOfFriends_'.$username);
    }

    /**
     * @param $player
     * @param int $friendId
     * @return Row|ActiveRow|null
     */
    public function isFriends($player, int $friendId) {
        $playerId = $this->getRowByName($player)->player_id;
        return $this->Explorer->table('fr_friend_assignment')->where('friend1_id = ? AND friend2_id = ? OR friend2_id = ? AND friend1_id=?',
            $playerId, $friendId, $playerId, $friendId)->fetch();
    }
}
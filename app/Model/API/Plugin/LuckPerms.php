<?php


namespace App\Model\API\Plugin;


use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * Class LuckPerms
 * @package App\Model\API\Plugin
 */
class LuckPerms
{
    const USER_TABLE_PERM = 'luckperms_user_permissions';
    const REGISTER_TABLE = 'luckperms_players';
    const GROUPS = [
        'helper' => 'group.helper',
        'owner' => 'group.majitel',
        'admin' => 'group.admini',
        'implement-web' => 'web.implement',
        'helpdesk' => 'web.helpdesk'
    ];

    private Explorer $Explorer;

    /**
     * LuckPerms constructor.
     * @param Explorer $Explorer
     * database.luckperms
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * Check if the player is a helper or has special privileges - helpdesk permission, default checking on server Hranice and global.
     * @param string $uuid
     * @param string $server
     * @return bool
     */
    public function isUserHelper(string $uuid, $server = 'hranice') {
        $rows = $this->Explorer->table(self::USER_TABLE_PERM)->where('uuid = ? AND server=? OR server=? OR server = ?', $uuid, $server, 'global', 'lobbyspawn')->fetchAll();
        foreach ($rows as $row) {
            if($row->permission == self::GROUPS['helper']
                || $row->permission == self::GROUPS['owner']
                || $row->permission == self::GROUPS['admin']
                || $row->permission == self::GROUPS['helpdesk'])
                return true;
        }
        return false;
    }

    /**
     * @param string $uuid
     * @return Selection
     */
    public function getPlayerPermissions(string $uuid) {
        return $this->Explorer->table(self::USER_TABLE_PERM)->where("uuid = ?", $uuid);
    }

    /**
     * @return Selection
     */
    public function getPlayers() {
        return $this->Explorer->table(self::REGISTER_TABLE)->order('username ASC');
    }

    /**
     * @param $nick
     * @return Row|ActiveRow|null
     */
    public function getUuidByNick($nick) {
        return $this->Explorer->table(self::REGISTER_TABLE)->where("username = ?", strtolower($nick))->fetch();
    }


    /**
     * @param $uuid
     * @return Row|ActiveRow|null
     */
    public function getNickByUuid($uuid) {
        return $this->Explorer->table(self::REGISTER_TABLE)->where("uuid = ?", $uuid)->fetch();
    }

    /**
     * @param $username
     * @return array|Row[]
     */
    public function getPlayerRows($username) {
        return $this->Explorer->query("SELECT t2.username, t1.permission, t1.server FROM luckperms_user_permissions AS t1 LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid WHERE username=?", $username)
                ->fetchAll();
    }

    /**
     * @return array|Row[]
     */
    public function getHelpers() {
        return $this->Explorer->query("SELECT t2.username, t1.permission, t1.server FROM luckperms_user_permissions AS t1 LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid WHERE permission = ? OR permission = ? OR permission = ? OR permission = ? OR permission = ? ORDER BY t2.username DESC",
            self::GROUPS['helper'], self::GROUPS['owner'], self::GROUPS['admin'], self::GROUPS['helpdesk'], self::GROUPS['implement-web'])->fetchAll();

    }

    /**
     * @param $uuid
     * @return array
     */
    public function getUserGroups($uuid) {
        $rows = $this->Explorer->table(self::USER_TABLE_PERM)->where('uuid = ?', $uuid)->fetchAll();
        $groups = [];
        foreach ($rows as $row) {
            if(mb_substr($row->permission, 0, 6) == 'group.')
                $groups[$row->server] = mb_substr($row->permission, 6, strlen($row->permission));
        }

        return $groups;
    }

    /**
     * @param $uuid
     * @param $permission
     * @return int
     */
    public function deleteSpecificPermission($uuid, $permission) {
        return $this->Explorer->table(LuckPerms::USER_TABLE_PERM)->where("uuid = ? AND permission = ?", $uuid, $permission)->delete();
    }
}
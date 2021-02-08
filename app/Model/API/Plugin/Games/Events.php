<?php


namespace App\Model\API\Plugin\Games;


use Nette\Database\Explorer;
use Nette\Database\ResultSet;
use Nette\Database\Table\Selection;

/**
 * Class Events
 * @package App\Model\API\Plugin
 */
class Events
{
    const EVENTS_TABLE = "events",
        PLAYERS_TABLE = "event_players";

    private Explorer $Explorer;

    /**
     * Events constructor.
     * @param Explorer $Explorer
     * database.events
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @return Selection
     */
    public function findAllEvents() {
        return $this->Explorer->table(self::EVENTS_TABLE);
    }

    /**
     * @return Selection
     */
    public function getActivePlayers() {
        return $this->Explorer->table(self::PLAYERS_TABLE)->where("event_passed > ? OR event_giveup > ?", 1,1);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getPlayerById($id) {
        return $this->Explorer->table(self::PLAYERS_TABLE)->where("id = ?", $id);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getEventById($id) {
        return $this->Explorer->table(self::EVENTS_TABLE)->where("event_id = ?", $id);
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getEventByName($name) {
        return $this->Explorer->table(self::EVENTS_TABLE)->where("event_name = ?", $name);
    }

    /**
     * @param $id
     * @param int|null $limit
     * @return Selection
     */
    public function getPlayersByEventId($id, ?int $limit = null) {
        return $this->Explorer->table(self::PLAYERS_TABLE)->where("event_id = ? AND event_giveup != 0 OR event_id = ? AND event_passed != 0", $id, $id)->order(
            "CAST(-best_time AS DECIMAL(16,2)) DESC, event_passed DESC, event_giveup DESC")->limit($limit);
    }

    /**
     * @param \stdClass $record
     * @param $id
     * @return int
     */
    public function updateRecord(\stdClass $record, $id) {
        return $this->Explorer->table(self::PLAYERS_TABLE)->where("id = ?", $id)->update([
            "username" => $record->username,
            "best_time" => $record->best_time,
            "event_passed" => $record->event_passed,
            "event_giveup" => $record->event_giveup,
            "best_played" => $record->best_played,
            "last_played" => $record->last_played
        ]);
    }

    /**
     * @param $nick
     * @param string $eventsTable
     * @param string $playersTable
     * @return ResultSet
     */
    public function getPlayerRecordsByName($nick, $eventsTable = self::EVENTS_TABLE, $playersTable = self::PLAYERS_TABLE) {
            return $this->Explorer
                ->query("SELECT * FROM {$playersTable} LEFT JOIN {$eventsTable} ON {$playersTable}.event_id = {$eventsTable}.event_id WHERE username=? GROUP BY username, event_players.event_id", $nick)->fetchAll();
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRecordById($id) {
        return $this->Explorer->table(self::PLAYERS_TABLE)->where("id = ?", $id)->delete();
    }
}
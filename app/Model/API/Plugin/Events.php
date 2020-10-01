<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class Events
 * @package App\Model\API\Plugin
 */
class Events
{
    const EVENTS_TABLE = "events",
        PLAYERS_TABLE = "event_players";

    private Context $context;

    /**
     * Events constructor.
     * @param Context $context
     * database.events
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return Selection
     */
    public function findAllEvents() {
        return $this->context->table(self::EVENTS_TABLE);
    }

    /**
     * @return Selection
     */
    public function getActivePlayers() {
        return $this->context->table(self::PLAYERS_TABLE)->where("event_passed > ? OR event_giveup > ?", 1,1);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getPlayerById($id) {
        return $this->context->table(self::PLAYERS_TABLE)->where("id = ?", $id);
    }

    public function getEventById($id) {
        return $this->context->table(self::EVENTS_TABLE)->where("event_id = ?", $id);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getPlayersByEventId($id) {
        return $this->context->table(self::PLAYERS_TABLE)->where("event_id = ? AND event_giveup != 0 OR event_id = ? AND event_passed != 0", $id, $id)->order(
            "CAST(-best_time AS DECIMAL) DESC, event_passed DESC, event_giveup DESC");
    }

    /**
     * @param \stdClass $record
     * @param $id
     * @return int
     */
    public function updateRecord(\stdClass $record, $id) {
        return $this->context->table(self::PLAYERS_TABLE)->where("id = ?", $id)->update([
            "username" => $record->username,
            "best_time" => $record->best_time,
            "event_passed" => $record->event_passed,
            "event_giveup" => $record->event_giveup,
            "best_played" => $record->best_played,
            "last_played" => $record->last_played
        ]);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRecordById($id) {
        return $this->context->table(self::PLAYERS_TABLE)->where("id = ?", $id)->delete();
    }
}
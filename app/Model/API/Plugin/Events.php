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
        PLAYERS_TABLE = "events_players";

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

    public function getPlayerById($id) {
        return $this->context->table("event_players")->where("id = ?", $id);
    }

    public function deleteRecordById($id) {
        return $this->context->table("event_players")->where("id = ?", $id)->delete();
    }
}
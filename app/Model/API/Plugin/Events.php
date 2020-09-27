<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

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
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
<?php


namespace App\Model\API\Plugin;


use Nette\Database\Explorer;

/**
 * Class PlayerTime
 * @package App\Model\API\Plugin
 */
class PlayerTime
{
    private Explorer $Explorer;

    const TABLE_NAME = "playtime";
    const PLAYER_WEEK = "playtime_week";

    /**
     * PlayerTime constructor.
     * @param Explorer $Explorer
     * database.playertime
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    public function getRowByName($name) {
        return $this->Explorer->table(self::TABLE_NAME)->where("LOWER(username) = ?", strtolower($name));
    }

    public function getWeekPlayer($username) {
        return $this->Explorer->table(self::PLAYER_WEEK)->where("LOWER(username) = ?", strtolower($username))->order("timestamp DESC");
    }

    /**
     * @return int
     */
    public function getAllPlayedTime(): int {
        $db = $this->Explorer->table(self::TABLE_NAME)->fetchAll();
        $time = 0;
        foreach ($db as $d) $time = $time + $d->playtime;
        return $time/60;
    }

    /**
     * @return Explorer
     */
    public function getExplorer(): Explorer
    {
        return $this->Explorer;
    }
}
<?php


namespace App\Model\API\Plugin;


use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

/**
 * Class OnlinePlayers
 * @package App\Model\API\Plugin
 */
class OnlinePlayers
{
    private Explorer $Explorer;

    const TABLE = "onlineplayers";

    /**
     * OnlinePlayers constructor.
     * @param Explorer $Explorer
     * database.onlineplayers
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @return Selection
     */
    public function getAllRows() {
        return $this->Explorer->table(self::TABLE);
    }

    /**
     * @return Selection
     */
    public function getOnlinePlayers() {
        return $this->Explorer->table(self::TABLE)->where("online = ?", 1);
    }
}
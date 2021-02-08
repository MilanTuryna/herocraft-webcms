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
    private Explorer $explorer;

    const TABLE = "onlineplayers";

    /**
     * OnlinePlayers constructor.
     * @param Explorer $explorer
     * database.onlineplayers
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @return Selection
     */
    public function getAllRows() {
        return $this->explorer->table(self::TABLE);
    }

    /**
     * @return Selection
     */
    public function getOnlinePlayers() {
        return $this->explorer->table(self::TABLE)->where("online = ?", 1);
    }
}
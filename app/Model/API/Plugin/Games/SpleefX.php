<?php


namespace App\Model\API\Plugin\Games;


use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

/**
 * Class SpleefX
 * @package App\Model\API\Plugin\Games
 */
class SpleefX
{
    const TABLE = "spleefxdata";

    private Explorer $explorer;

    /**
     * SpleefX constructor.
     * @param Explorer $explorer
     *
     * database.spleefx
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param $uuid
     * @return Selection
     */
    public function getRowByUuid($uuid) {
        return $this->explorer->table(self::TABLE)->where("PlayerUUID = ?", $uuid);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows($order = "Coins DESC") {
        return $this->explorer->table(self::TABLE)->order($order);
    }
}
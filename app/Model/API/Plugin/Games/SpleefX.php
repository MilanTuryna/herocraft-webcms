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

    private Explorer $Explorer;

    /**
     * SpleefX constructor.
     * @param Explorer $Explorer
     *
     * database.spleefx
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $uuid
     * @return Selection
     */
    public function getRowByUuid($uuid) {
        return $this->Explorer->table(self::TABLE)->where("PlayerUUID = ?", $uuid);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows($order = "Coins DESC") {
        return $this->Explorer->table(self::TABLE)->order($order);
    }
}
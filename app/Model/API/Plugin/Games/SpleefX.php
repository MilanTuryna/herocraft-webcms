<?php


namespace App\Model\API\Plugin\Games;


use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class SpleefX
 * @package App\Model\API\Plugin\Games
 */
class SpleefX
{
    const TABLE = "spleefxdata";

    private Context $context;

    /**
     * SpleefX constructor.
     * @param Context $context
     *
     * database.spleefx
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $uuid
     * @return Selection
     */
    public function getRowByUuid($uuid) {
        return $this->context->table(self::TABLE)->where("PlayerUUID = ?", $uuid);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows($order = "Coins DESC") {
        return $this->context->table(self::TABLE)->order($order);
    }
}
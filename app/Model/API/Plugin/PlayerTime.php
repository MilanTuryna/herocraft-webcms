<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

/**
 * Class PlayerTime
 * @package App\Model\API\Plugin
 */
class PlayerTime
{
    private Context $context;

    const TABLE_NAME = "playtime";

    /**
     * PlayerTime constructor.
     * @param Context $context
     * database.playertime
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getRowByName($name) {
        return $this->context->table(self::TABLE_NAME)->where("username = ?", $name);
    }
}
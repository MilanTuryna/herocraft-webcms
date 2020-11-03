<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class OnlinePlayers
 * @package App\Model\API\Plugin
 */
class OnlinePlayers
{
    private Context $context;

    const TABLE = "onlineplayers";

    /**
     * OnlinePlayers constructor.
     * @param Context $context
     * database.onlineplayers
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return Selection
     */
    public function getAllRows() {
        return $this->context->table(self::TABLE);
    }

    /**
     * @return Selection
     */
    public function getOnlinePlayers() {
        return $this->context->table(self::TABLE)->where("online = ?", 1);
    }
}
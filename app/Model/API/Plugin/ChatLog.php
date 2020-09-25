<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

/**
 * Class ChatLog
 * @package App\Model\API\Plugin
 */
class ChatLog
{
    private Context $context;

    const TABLE = 'mysql_logs';

    /**
     * ChatLog constructor.
     * @param Context $context
     * database.chatlog
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function findAllRows() {
        return $this->context->table(self::TABLE);
    }
}
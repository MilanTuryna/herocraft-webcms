<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;

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

    /**
     * @param string $columns
     * @return Selection
     */
    public function findAllRows(string $columns = '*') {
        return $this->context->table(self::TABLE)->select($columns);
    }

    /**
     * @param $nickname
     * @return array|IRow[]
     */
    public function getPlayerMessages(string $nickname) {
        return $this->context->table(self::TABLE)->where("Username", $nickname)->fetchAll();
    }

    /**
     * @return Context
     */
    public function getPluginDatabase(): Context {
        return $this->context;
    }
}
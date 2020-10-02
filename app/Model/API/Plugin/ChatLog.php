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
     * @param string $timeOrder
     * @return Selection
     */
    public function findAllRows(string $columns = '*', string $timeOrder = 'DESC') {
        return $this->context->table(self::TABLE)->select($columns)->order('Time ' . $timeOrder);
    }

    /**
     * @param array $players
     * @param string $timeStart
     * @param string $timeEnd
     * @param string $columns
     * @param string $timeOrder
     * @return Selection
     */
    public function filterAllRows(array $players, string $timeStart, string $timeEnd,
                                  string $columns = "*", string $timeOrder = 'DESC')
    {
        $timeStart = date_create($timeStart)->format('Y-m-d 00:00');
        $timeEnd = date_create($timeEnd)->format('Y-m-d 23:59');
        return $this->context->table(self::TABLE)->select($columns)
            ->where("Username",  $players)
            ->where("Time BETWEEN ? AND ?", $timeStart, $timeEnd)
            ->order('Time ' . $timeOrder);
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
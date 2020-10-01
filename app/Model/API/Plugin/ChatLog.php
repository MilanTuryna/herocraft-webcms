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
     * @param string $format
     * @param string $columns
     * @param string $timeOrder
     * @return Selection
     */
    public function filterAllRows(array $players, string $timeStart, string $timeEnd, string $format = "%d/%m/%y",
                                  string $columns = "*", string $timeOrder = 'DESC')
    {
        return $this->context->table(self::TABLE)->select($columns)
            ->where("Time > TIMESTAMP(STR_TO_DATE(?, ?)) AND Time < TIMESTAMP(STR_TO_DATE(?, ?), '23:59') AND Nickname IN (?)",
                $timeStart, $format, $timeEnd, $format, join("','", $players))
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
<?php


namespace App\Model\API\Plugin;


use Nette\Database\Explorer;
use Nette\Database\Table\Row;
use Nette\Database\Table\Selection;

/**
 * Class ChatLog
 * @package App\Model\API\Plugin
 */
class ChatLog
{
    private Explorer $explorer;

    const TABLE = 'mysql_logs';

    /**
     * ChatLog constructor.
     * @param Explorer $explorer
     * database.chatlog
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param string $columns
     * @param string $timeOrder
     * @return Selection
     */
    public function findAllRows(string $columns = '*', string $timeOrder = 'DESC') {
        return $this->explorer->table(self::TABLE)->select($columns)->order('Time ' . $timeOrder);
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
        return $this->explorer->table(self::TABLE)->select($columns)
            ->where("Username",  $players)
            ->where("Time BETWEEN ? AND ?", $timeStart, $timeEnd)
            ->order('Time ' . $timeOrder);
    }

    /**
     * @param $nickname
     * @return array|Row[]
     */
    public function getPlayerMessages(string $nickname) {
        return $this->explorer->table(self::TABLE)->where("Username", $nickname)->fetchAll();
    }

    /**
     * @return Explorer
     */
    public function getPluginDatabase(): Explorer {
        return $this->explorer;
    }
}
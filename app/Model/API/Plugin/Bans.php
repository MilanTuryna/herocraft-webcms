<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class Bans
 * @package App\Model\API\Plugin
 */
class Bans
{
    private Context $context;

    const BANS_TABLE = "bans";
    const IPBANS_TABLE = "ip_bans";

    /**
     * Bans constructor.
     * @param Context $context
     * database.bans
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $nick
     * @return int
     */
    public function deleteBanByNick($nick) {
        return $this->context->table(self::BANS_TABLE)->where("name = ?", strtolower($nick))->delete();
    }

    /**
     * @param array $players
     * @param string $timeStart
     * @param string $timeEnd
     * @param string $columns
     * @param string $timeOrder
     * @return Selection
     */
    public function filterAllRows(array $players, string $timeStart, string $timeEnd, string $columns = "*", string $timeOrder = "DESC") {
        $timeStart = date_create($timeStart)->getTimestamp()*1000;
        $timeEnd = date_create($timeEnd)->getTimestamp()*1000;
        return $this->context->table(self::BANS_TABLE)->select($columns)
            ->where("name",  $players)
            ->where("time BETWEEN ? AND ?", $timeStart, $timeEnd)
            ->order('time ' . $timeOrder);
    }

    /**
     * @param $ip
     * @return int
     */
    public function deleteBanByIP($ip) {
        return $this->context->table(self::IPBANS_TABLE)->where("ip = ?")->delete();
    }

    /**
     * @param $ip
     * @return Selection
     */
    public function getIPBanByIP($ip) {
        return $this->context->table(self::IPBANS_TABLE)->where("ip = ?");
    }

    /**
    * @param $nick
    * @return Selection
    */
    public function getBanByNick($nick) {
        return $this->context->table(self::BANS_TABLE)->where("name = ?", strtolower($nick));
    }

    /**
     * @return Selection
     */
    public function getAllIPBans() {
        return $this->context->table(self::IPBANS_TABLE);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllBans(string $order = 'DESC') {
        return $this->context->table(self::BANS_TABLE)->order("time " . $order);
    }

    /**
     * @param iterable $datas
     * @param $nick
     * @return int
     */
    public function updateBanByNick(iterable $datas, $nick) {
        return $this->context->table(self::BANS_TABLE)->where("name = ?", strtolower($nick))->update($datas);
    }

    /**
     * @return Context
     */
    public function getDatabaseContext(): Context {
        return $this->context;
    }
}
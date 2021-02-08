<?php


namespace App\Model\API\Plugin;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

/**
 * Class Bans
 * @package App\Model\API\Plugin
 */
class Bans
{
    private Explorer $Explorer;

    const BANS_TABLE = "bans";
    const IPBANS_TABLE = "ipbans";

    /**
     * Constant to replacing dots in IP when IP is giving to multiplier object but component name must be non-empty alphanumeric string
     */
    const MULTIPLIER_REPLACING_IP_CHARACTER = "z";

    /**
     * Bans constructor.
     * @param Explorer $Explorer
     * database.bans
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $nick
     * @return int
     */
    public function deleteBanByNick($nick) {
        return $this->Explorer->table(self::BANS_TABLE)->where("name = ?", strtolower($nick))->delete();
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
        $players = array_map('strtolower', $players);
        return $this->Explorer->table(self::BANS_TABLE)->select($columns)
            ->where("name",  $players)
            ->where("time BETWEEN ? AND ?", $timeStart, $timeEnd)
            ->order('time ' . $timeOrder);
    }

    public function filterAllIpBans(array $ips, string $timeStart, string $timeEnd, string $columns = "*", string $timeOrder = 'DESC') {
        $timeStart = date_create($timeStart)->getTimestamp()*1000;
        $timeEnd = date_create($timeEnd)->getTimestamp()*1000;
        return $this->Explorer->table(self::IPBANS_TABLE)->select($columns)
            ->where("ip",  $ips)
            ->where("time BETWEEN ? AND ?", $timeStart, $timeEnd)
            ->order('time ' . $timeOrder);
    }

    /**
     * @param $ip
     * @return int
     */
    public function deleteBanByIP($ip) {
        return $this->Explorer->table(self::IPBANS_TABLE)->where("ip = ?")->delete();
    }

    /**
     * @param $ip
     * @return Selection
     */
    public function getIPBanByIP($ip) {
        return $this->Explorer->table(self::IPBANS_TABLE)->where("ip = ?", $ip);
    }

    /**
    * @param $nick
    * @return Selection
    */
    public function getBanByNick($nick) {
        return $this->Explorer->table(self::BANS_TABLE)->where("name = ?", strtolower($nick));
    }

    /**
     * @return Selection
     */
    public function getAllIPBans() {
        return $this->Explorer->table(self::IPBANS_TABLE);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllBans(string $order = 'DESC') {
        return $this->Explorer->table(self::BANS_TABLE)->order("time " . $order);
    }

    /**
     * @param iterable $datas
     * @param $nick
     * @return int
     */
    public function updateBanByNick(iterable $datas, $nick) {
        return $this->Explorer->table(self::BANS_TABLE)->where("name = ?", strtolower($nick))->update($datas);
    }

    /**
     * @param iterable $datas
     * @param $ip
     * @return int
     */
    public function updateIpBanByIp(iterable $datas, $ip) {
        return $this->Explorer->table(self::IPBANS_TABLE)->where("ip = ?", $ip)->update($datas);
    }

    /**
     * @return Explorer
     */
    public function getDatabaseExplorer(): Explorer {
        return $this->Explorer;
    }
}
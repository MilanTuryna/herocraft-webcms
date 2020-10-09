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
     * @return Selection
     */
    public function getAllBans($order = 'DESC') {
        return $this->context->table(self::BANS_TABLE)->order("time DESC");
    }

    /**
     * @return Context
     */
    public function getDatabaseContext(): Context {
        return $this->context;
    }
}
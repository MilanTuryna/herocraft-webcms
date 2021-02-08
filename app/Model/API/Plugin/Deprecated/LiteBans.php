<?php


namespace App\Model\API\Plugin\Deprecated;


use Nette\Database\Explorer;

/**
 * Class LiteBans
 * @package App\Model\API\Plugin
 * @deprecated
 *
 */
class LiteBans
{
    private Explorer $Explorer;

    const BANS_TABLE = 'litebans_bans';
    const HISTORY_TABLE = 'litebans_history';

    /**
     * LiteBans constructor.
     * database.litebans
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isBanned($name) {
        $historyRow = $this->Explorer->table(self::HISTORY_TABLE)->where('name = ?', $name)->fetch();
        if($historyRow) {
            $uuid = $historyRow->uuid;
            $bansRow = $this->Explorer->table(self::BANS_TABLE)->where('uuid = ?', $uuid)->fetchAll();
            foreach ($bansRow as $ban) {
                if($ban->active) {
                    return true;
                }
            }
        }
        return false;
    }
}
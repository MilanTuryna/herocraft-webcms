<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

class LiteBans
{
    private Context $context;

    const BANS_TABLE = 'litebans_bans';
    const HISTORY_TABLE = 'litebans_history';

    /**
     * LiteBans constructor.
     * database.litebans
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isBanned($name) {
        $historyRow = $this->context->table(self::HISTORY_TABLE)->where('name = ?', $name)->fetch();
        if($historyRow) {
            $uuid = $historyRow->uuid;
            $bansRow = $this->context->table(self::BANS_TABLE)->where('uuid = ?', $uuid)->fetchAll();
            foreach ($bansRow as $ban) {
                if($ban->active) {
                    return true;
                }
            }
        }
        return false;
    }
}
<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class LuckPerms
 * @package App\Model\API\Plugin
 */
class LuckPerms
{
    const USER_TABLE_PERM = 'luckperms_user_permissions';
    const REGISTER_TABLE = 'luckperms_players';
    const GROUPS = [
        'helper' => 'group.helper',
        'owner' => 'group.majitel',
        'admin' => 'group.admini',
    ];

    private Context $context;

    /**
     * LuckPerms constructor.
     * @param Context $context
     * database.luckperms
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Oveří zda je hráč helper (použije se při HelpDesk authenticatoru)
     * @param string $uuid
     * @param string $server
     * @return bool
     */
    public function isUserHelper(string $uuid, $server = 'hranice') {
        $rows = $this->context->table(self::USER_TABLE_PERM)->where('uuid = ? AND server=?', $uuid, $server)->fetchAll();
        foreach ($rows as $row) {
            if($row->permission == self::GROUPS['helper']
                || $row->permission == self::GROUPS['owner']
                || $row->permission == self::GROUPS['admin'])
                return true;
        }
        return false;
    }

    /**
     * @param $nick
     * @return IRow|ActiveRow|null
     */
    public function getUuidByNick($nick) {
        return $this->context->table(self::REGISTER_TABLE)->where("username = ?", $nick)->fetch();
    }

    /**
     * @param $uuid
     * @return array
     */
    public function getUserGroups($uuid) {
        $rows = $this->context->table(self::USER_TABLE_PERM)->where('uuid = ?', $uuid)->fetchAll();
        $groups = [];
        foreach ($rows as $row) {
            if(mb_substr($row->permission, 0, 6) == 'group.') {
                $groups[$row->server] = mb_substr($row->permission, 6, strlen($row->permission));
            }
        }

        return $groups;
    }
}
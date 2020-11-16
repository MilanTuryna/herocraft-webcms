<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\ResultSet;
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
        'helpdesk' => 'web.helpdesk'
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
     * Check if the player is a helper or has special privileges - helpdesk permission, default checking on server Hranice and global.
     * @param string $uuid
     * @param string $server
     * @return bool
     */
    public function isUserHelper(string $uuid, $server = 'hranice') {
        $rows = $this->context->table(self::USER_TABLE_PERM)->where('uuid = ? AND server=? OR server=?', $uuid, $server, 'global')->fetchAll();
        foreach ($rows as $row) {
            if($row->permission == self::GROUPS['helper']
                || $row->permission == self::GROUPS['owner']
                || $row->permission == self::GROUPS['admin']
                || $row->permission == self::GROUPS['helpdesk'])
                return true;
        }
        return false;
    }

    /**
     * @param $nick
     * @return IRow|ActiveRow|null
     */
    public function getUuidByNick($nick) {
        return $this->context->table(self::REGISTER_TABLE)->where("username = ?", strtolower($nick))->fetch();
    }


    /**
     * @param $uuid
     * @return IRow|ActiveRow|null
     */
    public function getNickByUuid($uuid) {
        return $this->context->table(self::REGISTER_TABLE)->where("uuid = ?", $uuid)->fetch();
    }

    /**
     * @param $username
     * @return IRow
     */
    public function getPlayerRow($username) {
        return $this->context->query("SELECT t2.username, t1.permission, t1.server FROM luckperms_user_permissions AS t1 LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid WHERE username=?", $username)
                ->fetch();
    }

    /**
     * @return array|IRow[]
     */
    public function getHelpers() {
        return $this->context->query("SELECT t2.username, t1.permission, t1.server FROM luckperms_user_permissions AS t1 LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid WHERE permission = ? OR permission = ? OR permission = ? OR permission = ?",
            self::GROUPS['helper'], self::GROUPS['owner'], self::GROUPS['admin'], self::GROUPS['helpdesk'])->fetchAll();

    }

    /**
     * @param $uuid
     * @return array
     */
    public function getUserGroups($uuid) {
        $rows = $this->context->table(self::USER_TABLE_PERM)->where('uuid = ?', $uuid)->fetchAll();
        $groups = [];
        foreach ($rows as $row) {
            if(mb_substr($row->permission, 0, 6) == 'group.')
                $groups[$row->server] = mb_substr($row->permission, 6, strlen($row->permission));
        }

        return $groups;
    }
}
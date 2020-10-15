<?php


namespace App\Model\API\Plugin\Deprecated;


use Nette\Database\Context;

/**
 * Class LuckPerms
 * @package App\Model\API\Plugin
 * @deprecated
 */
class LuckPerms
{
    const USER_TABLE_PERM = 'luckperms_user_permissions';
    const GROUPS = [
        'developer' => 'group.developer',
        'helper' => 'group.helper',
        'owner' => 'group.owner',
        'admin' => 'group.admin'
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
     * @return bool
     */
    public function isUserHelper(string $uuid) {
        $rows = $this->context->table(self::USER_TABLE_PERM)->where('uuid = ?', $uuid)->fetchAll();
        foreach ($rows as $row) {
            if($row->permission == self::GROUPS['helper']
                || $row->permission == self::GROUPS['owner']
                || $row->permission == self::GROUPS['admin']
                || $row->permission == self::GROUPS['developer'])
                return true;
        }
        return false;
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
                array_push($groups, mb_substr($row->permission, 6, strlen($row->permission)));
            }
        }

        return $groups;
    }
}
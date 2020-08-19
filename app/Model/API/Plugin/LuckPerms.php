<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

class LuckPerms
{
    const USER_TABLE_PERM = 'luckperms_users_permissions';
    const GROUPS = [
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
            if($row->permission == self::GROUPS['helper'] || $row->permission == self::GROUPS['owner'] || $row->permission == self::GROUPS['admin'])
                return true;
        }
        return false;
    }
}
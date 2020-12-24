<?php

namespace App\Model\Panel;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class AuthMeRepository
 * @package App\Model\Panel
 */
class AuthMeRepository
{
    private Context $context;

    /**
     * AuthMeRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getRegisterCount(): int {
        return $this->context->table('authme')->count("*");
    }

    /**
     * @param $username
     * @return IRow|ActiveRow|null
     */
    public function findByUsername($username) {
        return $this->context->table('authme')->where('username = ?', strtolower($username))->limit(1)->fetch();
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function findById($id): ?ActiveRow {
        return $this->context->table('authme')->get($id);
    }

    /**
     * @param $hashedPassword
     * @param $id
     * @return int
     */
    public function changePassword($hashedPassword, $id): int {
        return $this->context->table('authme')->where('id = ?', $id)->update([
            'password' => $hashedPassword
        ]);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int {
        return $this->context->table('authme')->where('id = ?', $id)->delete();
    }
}
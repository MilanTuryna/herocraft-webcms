<?php

namespace App\Model\Panel;

use Nette\Database\Context;

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

    public function findByUsername($username) {
        return $this->context->table('authme')->where('username = ?', strtolower($username))->limit(1)->fetch();
    }

    public function findById($id) {
        return $this->context->table('authme')->get($id);
    }

    /**
     * @param $hashedPassword
     * @param $id
     * @return int
     */
    public function changePassword($hashedPassword, $id) {
        return $this->context->table('authme')->where('id = ?', $id)->update([
            'password' => $hashedPassword
        ]);
    }

    public function delete($id) {
        return $this->context->table('authme')->where('id = ?', $id)->delete();
    }
}
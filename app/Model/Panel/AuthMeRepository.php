<?php

namespace App\Model\Panel;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class AuthMeRepository
 * @package App\Model\Panel
 */
class AuthMeRepository
{
    private Explorer $Explorer;

    /**
     * AuthMeRepository constructor.
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @return int
     */
    public function getRegisterCount(): int {
        return $this->Explorer->table('authme')->count("*");
    }

    /**
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function findByUsername($username) {
        return $this->Explorer->table('authme')->where('username = ?', strtolower($username))->limit(1)->fetch();
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function findById($id): ?ActiveRow {
        return $this->Explorer->table('authme')->get($id);
    }

    /**
     * @param $hashedPassword
     * @param $id
     * @return int
     */
    public function changePassword($hashedPassword, $id): int {
        return $this->Explorer->table('authme')->where('id = ?', $id)->update([
            'password' => $hashedPassword
        ]);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int {
        return $this->Explorer->table('authme')->where('id = ?', $id)->delete();
    }
}
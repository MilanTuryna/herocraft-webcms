<?php


namespace App\Model;


use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class UserRepository
 * @package App\Model
 */
class UserRepository
{
    private Explorer $Explorer;

    /**
     * UserRepository constructor.
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @return array
     */
    public function findAll(): array {
        return $this->Explorer->table('admin')->fetchAll();
    }

    /**
     * @param $name
     * @return Row|ActiveRow|null
     */
    public function findByName($name) { // or email
        return $this->Explorer->table('admin')->where('name = ? OR email = ?', $name, $name)->fetch();
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function findById($id) {
        return $this->Explorer->table('admin')->get($id);
    }

    /**
     * @param $user
     * @param $email
     * @param $pass
     * @param $permissions
     * @return bool|int|ActiveRow
     */
    public function addUser($user, $email, $pass, $permissions) {
        return $this->Explorer->table('admin')->insert([
            'name' => $user,
            'email' => $email,
            'pass' => $pass,
            'permissions' => $permissions,
        ]);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteUser($id) {
        return $this->Explorer->table('admin')->wherePrimary($id)->delete();
    }

    /**
     * @param $id
     * @param iterable $values
     * @return int
     */
    public function update($id, iterable $values) {
        return $this->Explorer->table('admin')->wherePrimary($id)->update($values);
    }

    /**
     * @return int
     */
    public function countAll() {
        return $this->Explorer->table('admin')->count('*');
    }
}
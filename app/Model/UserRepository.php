<?php


namespace App\Model;


use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class UserRepository
 * @package App\Model
 */
class UserRepository
{
    private Context $context;

    /**
     * UserRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function findAll(): array {
        return $this->context->table('admin')->fetchAll();
    }

    /**
     * @param $name
     * @return IRow|ActiveRow|null
     */
    public function findByName($name) { // or email
        return $this->context->table('admin')->where('name = ? OR email = ?', $name, $name)->fetch();
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function findById($id) {
        return $this->context->table('admin')->get($id);
    }

    /**
     * @param $user
     * @param $email
     * @param $pass
     * @param $permissions
     * @return bool|int|ActiveRow
     */
    public function addUser($user, $email, $pass, $permissions) {
        return $this->context->table('admin')->insert([
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
        return $this->context->table('admin')->wherePrimary($id)->delete();
    }

    /**
     * @param $id
     * @param iterable $values
     * @return int
     */
    public function update($id, iterable $values) {
        return $this->context->table('admin')->wherePrimary($id)->update($values);
    }

    /**
     * @return int
     */
    public function countAll() {
        return $this->context->table('admin')->count('*');
    }
}
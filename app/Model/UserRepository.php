<?php


namespace App\Model;


use Nette\Database\Context;

class UserRepository
{
    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function findAll(): array {
        return $this->context->table('admin')->fetchAll();
    }

    public function findByName($name) { // or email
        return $this->context->table('admin')->where('name = ? OR email = ?', $name, $name)->fetch();
    }

    public function findById($id) {
        return $this->context->table('admin')->get($id);
    }

    public function addUser($user, $email, $pass, $permission) {
        return $this->context->table('admin')->insert([
            'name' => $user,
            'email' => $email,
            'pass' => $pass,
            'permission' => $permission,
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
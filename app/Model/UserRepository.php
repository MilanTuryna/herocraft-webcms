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

    public function addUser($user, $email, $pass) {
        return $this->context->table('admin')->insert([
            'name' => $user,
            'email' => $email,
            'pass' => $pass
        ]);
    }

    public function deleteUser($id) {
        return $this->context->table('admin')->wherePrimary($id)->delete();
    }

    public function update($id, array $values) {
        $this->context->table('admin')->wherePrimary($id)->update($values);
    }

    public function countAll() {
        return $this->context->table('admin')->count('*');
    }
}
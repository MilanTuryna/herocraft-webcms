<?php


namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Class DuplicateNameException
 * @package App\Model
 */
final class DuplicateNameException extends \Exception {}

/**
 * Class UserManager
 * @package App\Model
 * @see nette\sandbox
 */
final class UserManager
{
    use Nette\SmartObject;

    /** @var Passwords */
    private Passwords $passwords;

    private UserRepository $userRepository;

    /**
     * UserManager constructor.
     * @param Passwords $passwords
     * @param UserRepository $userRepository
     */
    public function __construct(Passwords $passwords, UserRepository $userRepository)
    {
        $this->passwords = $passwords;
        $this->userRepository = $userRepository;
    }

    /**
     * Adds new user.
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws DuplicateNameException
     */
    public function add(string $username, string $email, string $password): void
    {
        try {
            $this->userRepository->addUser($username, $email, $this->passwords->hash($password));
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $pass
     * @param $email
     * @throws DuplicateNameException
     */
    public function edit($id, $name, $email, $pass): void {
        try {
            $arr =  [
                'name' => $name,
                'email' => $email
            ];
            if($pass) $arr['pass'] = $this->passwords->hash($pass);
            $this->userRepository->update($id, $arr);
        } catch(Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }

    public function delete() {

    }

    /** @return UserRepository */
    public function getRepository(): UserRepository {
        return $this->userRepository;
    }

}
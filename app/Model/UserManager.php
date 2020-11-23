<?php


namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use App\Model\Security\Exceptions\DuplicateNameException;

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
     * @param string $permissions
     * @throws DuplicateNameException
     */
    public function add(string $username, string $email, string $password, string $permissions): void
    {
        try {
            $this->userRepository->addUser($username, $email, $this->passwords->hash($password), $permissions);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $email
     * @param $pass
     * @param $permissions
     * @throws DuplicateNameException
     */
    public function edit($id, $name, $email, $pass, $permissions): void {
        try {
            $arr =  [
                'name' => $name,
                'email' => $email,
                'permissions' => $permissions,
            ];
            if($pass) $arr['pass'] = $this->passwords->hash($pass);
            $this->userRepository->update($id, $arr);
        } catch(Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }

    /** @return UserRepository */
    public function getRepository(): UserRepository {
        return $this->userRepository;
    }

}
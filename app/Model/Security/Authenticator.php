<?php

namespace App\Model\Security;

use App\Model\UserRepository;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Session;
use Nette\Security\Passwords;

/**
 * Class Authenticator
 * @package App\Model\Security
 */
class Authenticator implements IAuthenticator
{
    const SESSION_SECTION = 'admin_login';

    private UserRepository $userRepository;
    private Passwords $passwords;
    private Session $session;

    /**
     * Authenticator constructor.
     * @param UserRepository $userRepository
     * @param Passwords $passwords
     * @param Session $session
     */
    public function __construct(UserRepository $userRepository, Passwords $passwords, Session $session)
    {
        $this->userRepository = $userRepository;
        $this->passwords = $passwords;
        $this->session = $session;
    }

    /**
     * @param $credentials
     * @param string $expiration
     * @throws AuthException
     */
    public function login(array $credentials, string $expiration = IAuthenticator::EXPIRATION): void {
        [$name, $password] = $credentials;
        $user = $this->userRepository->findByName($name);

        $section = $this->session->getSection(self::SESSION_SECTION);
        $section->setExpiration($expiration);

        if($user) {
            if($this->passwords->verify($password, $user->pass)) {
                $section->id = $user->id;
            } else {
                throw new AuthException('Zadal jsi nesprávné heslo nebo jméno!');
            }
        } else {
            throw new AuthException('Zadal jsi nesprávné heslo nebo jméno!');
        }
    }

    /**
     * @return bool|ActiveRow|null
     */
    public function getUser() {
        if($this->session->getSection(self::SESSION_SECTION)->id) {
            return $this->userRepository->findById($this->session->getSection(self::SESSION_SECTION)->id);
        }
        return false;
    }

    /**
     * @return bool
     * @deprecated
     *
     * Use (bool)getUser();
     */
    public function isLoggedIn(): bool {
        return $this->userRepository->findById($this->session->getSection(self::SESSION_SECTION)->id) ? true : false;
    }

    /**
     * @throws AuthException
     */
    public function logout(): void {
        $section = $this->session->getSection(self::SESSION_SECTION);
        if($section->id) {
            $section->remove();
        } else {
            throw new AuthException("Nemůžeš se odhlašovat, když nejsi přihlášený!");
        }
    }

}
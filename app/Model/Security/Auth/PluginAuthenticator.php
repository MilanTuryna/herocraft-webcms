<?php


namespace App\Model\Security\Auth;

use App\Model\Panel\AuthMeRepository;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Session;
use App\Model\API\AuthMe;
use App\Model\Security\Exceptions\AuthException;

/**
 * Class PluginAuthenticator
 * @package App\Model\Security
 *
 * Authenticator for AuthMe on Minecraft server
 */
class PluginAuthenticator implements IAuthenticator
{
    const SESSION_SECTION = 'authme_login';

    private AuthMeRepository $authMeRepository;
    private Session $session;

    /**
     * PluginAuthenticator constructor.
     * @param AuthMeRepository $authMeRepository
     * @param Session $session
     */
    public function __construct(AuthMeRepository $authMeRepository, Session $session)
    {
        $this->authMeRepository = $authMeRepository;
        $this->session = $session;
    }

    /**
     * @param array $credentials
     * @param string $expiration
     * @throws AuthException
     */
    public function login(array $credentials, string $expiration = IAuthenticator::EXPIRATION): void
    {
        [$name, $password] = $credentials;
        $user = $this->authMeRepository->findByUsername($name);
        $section = $this->session->getSection(self::SESSION_SECTION);
        $section->setExpiration($expiration);

        if($user && @AuthMe::isValidLogin($password, $user->password)) {
            $section->id = $user->id;
        } else {
            throw new AuthException('Zadal jsi nesprávný nick nebo heslo!');
        }
    }

    /**
     * @throws AuthException
     */
    public function logout(): void
    {
        $section = $this->session->getSection(self::SESSION_SECTION);
        if($section->id) {
            $section->remove();
        } else {
            throw new AuthException("Nemůžeš se odhlašovat, když nejsi přihlášený!");
        }
    }

    /**
     * @return bool|ActiveRow|null
     */
    public function getUser() {
        if($this->session->getSection(self::SESSION_SECTION)->id) {
            return $this->authMeRepository->findById($this->session->getSection(self::SESSION_SECTION)->id);
        }
        return false;
    }
}
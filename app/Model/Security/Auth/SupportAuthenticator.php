<?php


namespace App\Model\Security\Auth;


use App\Model\API\AuthMe;
use App\Model\API\Plugin\LuckPerms;
use App\Model\Panel\AuthMeRepository;
use App\Model\Panel\MojangRepository;
use App\Model\Security\Exceptions\AuthException;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Session;

/**
 * Class SupportAuthenticator
 * @package App\Model\Security\Auth
 */
class SupportAuthenticator implements IAuthenticator
{
    const SESSION_SECTION = "helpDesk_login";

    private AuthMeRepository $authMeRepository;
    private Session $session;
    private LuckPerms $luckPerms;
    private MojangRepository $mojangRepository;
    private AuthMe $authMe;

    /**
     * SupportAuthenticator constructor.
     * @param AuthMeRepository $authMeRepository
     * @param Session $session
     * @param LuckPerms $luckPerms
     * @param MojangRepository $mojangRepository
     */
    public function __construct(AuthMeRepository $authMeRepository, Session $session, LuckPerms $luckPerms, MojangRepository $mojangRepository)
    {
        $this->authMeRepository = $authMeRepository;
        $this->session = $session;
        $this->luckPerms = $luckPerms;
        $this->mojangRepository = $mojangRepository;
        $this->authMe = new AuthMe();
    }

    /**
     * @inheritDoc
     * @throws AuthException
     */
    public function login(array $credentials, string $expiration = self::EXPIRATION): void
    {

        [$name, $password] = $credentials;
        $user = $this->authMeRepository->findByUsername($name);
        $section = $this->session->getSection(self::SESSION_SECTION);
        $section->setExpiration($expiration);

        if($user && $this->authMe->isValidPassword($password, $user->password)) {
            if($this->luckPerms->isUserHelper($this->mojangRepository->getUUID($name))) {
                $section->id = $user->id;
            } else {
                throw new AuthException('K tomuto webu nemáš jako hráč přístup!');
            }
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

    /**
     * @return AuthMeRepository
     */
    public function getAuthMeRepository() {
        return $this->authMeRepository;
    }

    /**
     * @return MojangRepository
     */
    public function getMojangRepository() {
        return $this->mojangRepository;
    }
}
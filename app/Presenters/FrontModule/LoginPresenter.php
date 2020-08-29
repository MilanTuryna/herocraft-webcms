<?php


namespace App\Presenters\FrontModule;

use App\Model\SettingsRepository;
use Nette;
use Nette\Database\Context;

use App\Presenters\BasePresenter;
use App\Model\Security\Auth\Authenticator;
use App\Forms\Front\SignInForm;
use App\Model\Security\Exceptions\AuthException;
use App\Model\API\Status;

/**
 * Class LoginPresenter
 * @package App\Presenters\FrontModule
 */
class LoginPresenter extends BasePresenter
{
    use Nette\SmartObject;

    private SettingsRepository $settingsRepository;
    private Authenticator $authenticator;
    private Context $context;
    private Nette\Caching\Cache $cache;

    /**
     * LoginPresenter constructor.
     * @param Authenticator $authenticator
     * @param Context $context
     * @param Nette\Caching\IStorage $storage
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Authenticator $authenticator, Context $context, Nette\Caching\IStorage $storage, SettingsRepository $settingsRepository)
    {
        parent::__construct();

        $this->settingsRepository = $settingsRepository;
        $this->context = $context;
        $this->cache = new Nette\Caching\Cache($storage);
        $this->authenticator = $authenticator;
    }

    /**
     * @throws Nette\Application\AbortException
     */
    public function renderMain() {
        $nastaveni = $this->context->table('nastaveni')->get(1);
        $status = new Status((string)$nastaveni->ip, $this->cache);
        $this->template->nastaveni = $nastaveni;
        $this->template->widget = $this->settingsRepository->getContext()->table('widget')->wherePrimary(1)->fetch();
        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->stranky = $this->context->table('pages');
        $this->template->status = !$nastaveni->udrzba ? $status->getCachedJson() : false; // pokud neni udrzba nebo api nefunguje, status se vypise jinak false

        if((bool)$this->authenticator->getUser()) {
            $this->redirect(':Admin:Main:home');
        }
    }

    /**
     * @throws Nette\Application\AbortException
     */
    public function actionLogout() {
        try {
            $this->authenticator->logout();
            $this->flashMessage('Byl jsi odhlášen, pro další manipulaci s administrací se přihlaš!', 'success');
            $this->redirect('Login:main');
        } catch (AuthException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
        }
    }

    /**
     * @return Nette\Application\UI\Form
     */
    public function createComponentSignInForm() {
        return (new SignInForm($this, $this->authenticator))->create();
    }
}
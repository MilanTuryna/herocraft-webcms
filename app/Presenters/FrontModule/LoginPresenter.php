<?php


namespace App\Presenters\FrontModule;

use App\Model\DI\GoogleAnalytics;
use App\Model\SettingsRepository;
use Nette;
use Nette\Database\Explorer;

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
    private Explorer $Explorer;
    private Nette\Caching\Cache $cache;

    /**
     * LoginPresenter constructor.
     * @param Authenticator $authenticator
     * @param Explorer $Explorer
     * @param Nette\Caching\Storage $storage
     * @param SettingsRepository $settingsRepository
     * @param GoogleAnalytics $googleAnalytics
     */
    public function __construct(Authenticator $authenticator, Explorer $Explorer, Nette\Caching\Storage $storage, SettingsRepository $settingsRepository, GoogleAnalytics $googleAnalytics)
    {
        parent::__construct($googleAnalytics);

        $this->settingsRepository = $settingsRepository;
        $this->Explorer = $Explorer;
        $this->cache = new Nette\Caching\Cache($storage);
        $this->authenticator = $authenticator;
    }

    /**
     * @throws Nette\Application\AbortException
     */
    public function renderMain() {
        $nastaveni = $this->settingsRepository->getAllRows();
        $status = new Status((string)$nastaveni->ip, $this->cache);
        $this->template->nastaveni = $nastaveni;
        $this->template->widget = $this->settingsRepository->getWidgetCode(1);
        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->stranky = $this->Explorer->table('pages');
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
            $this->flashMessage($this->translator->translate("front.flashMessages.successLogout"), 'success');
            $this->redirect('Login:main');
        } catch (AuthException $e) {
            $this->redirect("Login:main");
        }
    }

    /**
     * @return Nette\Application\UI\Form
     */
    public function createComponentSignInForm() {
        return (new SignInForm($this, $this->authenticator))->create();
    }
}
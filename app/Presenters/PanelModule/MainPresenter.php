<?php
namespace App\Presenters\PanelModule;

use App\Forms\Panel\Main\ChangePasswordForm;
use App\Model\API\Minecraft;
use App\Model\DI\GoogleAnalytics;
use App\Model\Panel\AuthMeRepository;
use App\Model\Panel\MojangRepository;
use App\Model\Panel\Object\MojangUser;
use App\Model\Security\Auth\PluginAuthenticator;
use App\Model\SettingsRepository;
use App\Model\Stats\CachedAPIRepository;
use App\Presenters\PanelBasePresenter;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;

/**
 * Class MainPresenter
 * @package App\Presenters\PanelModule
 */
class MainPresenter extends PanelBasePresenter
{
    private PluginAuthenticator $pluginAuthenticator;
    private ActiveRow $user;
    private MojangRepository $mojangRepository;
    private CachedAPIRepository $cachedAPIRepository;
    private AuthMeRepository $authMeRepository;
    private GoogleAnalytics $googleAnalytics;

    /**
     * MainPresenter constructor.
     * @param PluginAuthenticator $pluginAuthenticator
     * @param SettingsRepository $settingsRepository
     * @param AuthMeRepository $authMeRepository
     * @param MojangRepository $mojangRepository
     * @param CachedAPIRepository $cachedAPIRepository
     * @param GoogleAnalytics $googleAnalytics
     */
    public function __construct(PluginAuthenticator $pluginAuthenticator,
                                SettingsRepository $settingsRepository,
                                AuthMeRepository $authMeRepository,
                                MojangRepository $mojangRepository,
                                CachedAPIRepository $cachedAPIRepository,
                                GoogleAnalytics $googleAnalytics)
    {
        parent::__construct($settingsRepository, $googleAnalytics);

        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->pluginAuthenticator = $pluginAuthenticator;
        $this->mojangRepository = $mojangRepository;
        $this->authMeRepository = $authMeRepository;
        $this->googleAnalytics = $googleAnalytics;
    }

    /**
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();
        $user = $this->pluginAuthenticator->getUser();
        if(!(bool)$user) {
            $this->flashMessage($this->translator->translate("panel.flashMessages.pleaseAuthorize"), 'error');
            $this->redirect('Login:main');
        } else {
            $this->user = $user;
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();

        $networkStats = new \stdClass();

        $this->template->isBanned = $this->cachedAPIRepository->isBanned($this->user->realname);
        $this->template->user = $this->user;
        $this->template->networkStats = $networkStats;
    }

    public function renderHome()
    {
        $mcUser = new MojangUser($this->mojangRepository->getUUID($this->user->realname), Minecraft::getSkinURL($this->mojangRepository->getMojangUUID($this->user->realname)));
        $this->template->mcUser = $mcUser;
    }

    /**
     * @return Form
     */
    public function createComponentChangePassForm(): Form {
        return (new ChangePasswordForm($this, $this->user, $this->authMeRepository))->create();
    }
}
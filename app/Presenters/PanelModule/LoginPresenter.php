<?php

namespace App\Presenters\PanelModule;

use App\Forms\Panel\SignInForm;
use App\Model\DI\GoogleAnalytics;
use App\Model\Security\Auth\PluginAuthenticator;
use App\Model\Security\Exceptions\AuthException;
use App\Model\SettingsRepository;
use App\Presenters\PanelBasePresenter;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\InvalidLinkException;

/**
 * Class LoginPresenter
 * @package App\Presenters\PanelModule
 */
class LoginPresenter extends PanelBasePresenter
{
    /**
     * @var string
     * @persistent
     */
    public string $backLink = '';
    private PluginAuthenticator $pluginAuthenticator;
    private SettingsRepository $settingsRepository;

    /**
     * LoginPresenter constructor.
     * @param PluginAuthenticator $pluginAuthenticator
     * @param SettingsRepository $settingsRepository
     * @param GoogleAnalytics $googleAnalytics
     */
    public function __construct(PluginAuthenticator $pluginAuthenticator, SettingsRepository $settingsRepository, GoogleAnalytics $googleAnalytics)
    {
        parent::__construct($settingsRepository, $googleAnalytics);

        $this->pluginAuthenticator = $pluginAuthenticator;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @throws AbortException
     */
    public function renderMain() {
        if((bool)$this->pluginAuthenticator->getUser()) {
            $this->restoreRequest($this->backLink);
            $this->redirect('Main:home');
        }
    }

    /**
     * @throws AbortException
     */
    public function renderTicketLogin() {
        $this->renderMain();
    }

    /**
     * @param string $backLink
     * @throws AbortException
     */
    public function actionLogout(string $backLink) {
        try {

            $this->pluginAuthenticator->logout();
            $this->flashMessage($this->translator->translate("panel.flashMessages.successLogout"), 'dark-green');
            $this->backLink = $backLink;
            $this->redirect('Login:main');
        } catch (AuthException $e) {
            $this->flashMessage($e->getMessage(), 'error');
        }
    }

    /**
     * @return Form
     */
    public function createComponentSignInForm(): Form {
        return (new SignInForm($this->pluginAuthenticator, $this, $this->backLink))->create();
    }
}
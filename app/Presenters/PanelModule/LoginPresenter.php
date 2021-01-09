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
        $returnRoute = $this->getHttpRequest()->getQuery("returnRoute");
        $this->template->returnRoute = $returnRoute;
        if((bool)$this->pluginAuthenticator->getUser()) {
            if($returnRoute) try {
                $this->link($returnRoute);
                $this->redirect($returnRoute);
            } catch (InvalidLinkException $invalidLinkException) {}
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
     * @throws AbortException
     */
    public function actionLogout() {
        try {
            $this->pluginAuthenticator->logout();
            $this->flashMessage($this->translator->translate("panel.flashMessages.successLogout"), 'dark-green');
            $this->redirect('Login:main');
        } catch (AuthException $e) {
            $this->flashMessage($e->getMessage(), 'error');
        }
    }

    /**
     * @return Form
     */
    public function createComponentSignInForm(): Form {
        return (new SignInForm($this->pluginAuthenticator, $this))->create();
    }
}
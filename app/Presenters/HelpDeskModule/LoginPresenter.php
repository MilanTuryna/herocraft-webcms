<?php

namespace App\Presenters\HelpDeskModule;

use App\Forms\Front\SignInForm;
use App\Model\Security\Auth\SupportAuthenticator;
use App\Model\SettingsRepository;
use App\Presenters\HelpBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;

/**
 * Class LoginPresenter
 * @package App\Presenters\HelpDeskModule
 */
class LoginPresenter extends HelpBasePresenter
{
    private SupportAuthenticator $supportAuthenticator;

    /**
     * LoginPresenter constructor.
     * @param SupportAuthenticator $supportAuthenticator
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SupportAuthenticator $supportAuthenticator, SettingsRepository $settingsRepository)
    {
        parent::__construct($settingsRepository);

        $this->supportAuthenticator = $supportAuthenticator;
    }

    /**
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();

        if((bool)$this->supportAuthenticator->getUser()) $this->redirect("Main:home");
    }

    /**
     * @return Form
     */
    public function createComponentSignInForm() {
        return (new SignInForm($this, $this->supportAuthenticator, "Main:home"))->create();
    }
}
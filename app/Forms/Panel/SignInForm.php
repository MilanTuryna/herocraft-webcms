<?php

namespace App\Forms\Panel;

use App\Model\Security\Exceptions\AuthException;
use App\Model\Security\Auth\PluginAuthenticator;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

class SignInForm
{
    private PluginAuthenticator $pluginAuthenticator;
    private Presenter $presenter;

    /**
     * SignInForm constructor.
     * @param PluginAuthenticator $pluginAuthenticator
     * @param Presenter $presenter
     */
    public function __construct(PluginAuthenticator $pluginAuthenticator, Presenter $presenter)
    {
        $this->pluginAuthenticator = $pluginAuthenticator;
        $this->presenter = $presenter;
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Nick')
            ->setRequired();
        $form->addPassword('password', 'Heslo')->setRequired();
        $form->addSubmit('submit')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        try {
            $this->pluginAuthenticator->login([$values->name, $values->password]);
            $this->presenter->flashMessage('Byl jsi úspěšně autorizován a přihlášen!', 'success');
            $this->presenter->redirect(':Panel:Main:home');
        } catch (AuthException $e) {
            $form->addError($e->getMessage());
        }
    }
}
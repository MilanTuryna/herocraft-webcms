<?php

namespace App\Forms\Front;

use App\Model\Security\Authenticator;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use App\Model\UserManager;
use App\Model\Security\AuthException;

/**
 * Class SignInForm
 * @package App\Forms\Admin\Front
 */
class SignInForm
{
    private Presenter $presenter;
    private Authenticator $authenticator;

    /**
     * SignInForm constructor.
     * @param Presenter $presenter
     * @param Authenticator $authenticator
     */
    public function __construct(Presenter $presenter, Authenticator $authenticator)
    {
        $this->presenter = $presenter;
        $this->authenticator = $authenticator;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('name')->setRequired();
        $form->addPassword('password')->setRequired();
        $form->addSubmit('submit')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        try {
            $this->authenticator->login([$values->name, $values->password]);
            $this->presenter->flashMessage('Byl jsi úspěšně autorizován a přihlášen!', 'success');
            $this->presenter->redirect(':Admin:Main:home');
        } catch (AuthException $e) {
            $form->addError($e->getMessage());
        }
    }
}
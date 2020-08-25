<?php

namespace App\Forms\Front;

use App\Model\Security\Auth\IAuthenticator;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use App\Model\Security\Exceptions\AuthException;

/**
 * Class SignInForm
 * @package App\Forms\Admin\Front
 */
class SignInForm
{
    private Presenter $presenter;
    private IAuthenticator $authenticator;
    private string $redirect;

    /**
     * SignInForm constructor.
     * @param Presenter $presenter
     * @param IAuthenticator $authenticator
     * @param string $redirect
     */
    public function __construct(Presenter $presenter, IAuthenticator $authenticator, $redirect = ':Admin:Main:home')
    {
        $this->presenter = $presenter;
        $this->authenticator = $authenticator;
        $this->redirect = $redirect;
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
            $this->presenter->redirect($this->redirect);
        } catch (AuthException $e) {
            $form->addError($e->getMessage());
        }
    }
}
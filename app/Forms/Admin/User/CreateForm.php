<?php

namespace App\Forms\Admin\User;

use App\Model\Admin\Roles\Permissions;
use App\Model\DuplicateNameException;
use App\Model\UserManager;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

class CreateForm
{
    private UserManager $userManager;
    private Presenter $presenter;

    public function __construct(UserManager $userManager, Presenter $presenter)
    {
        $this->userManager = $userManager;
        $this->presenter = $presenter;
    }

    public function create() {
        $form = new Form;
        $form->addText('name', 'Uživatel')
            ->setRequired()
            ->setMaxLength(25);
        $form->addCheckboxList('permissions', "Práva", Permissions::getSelectBox());
        $form->addEmail('email', 'Email')
            ->setRequired()
            ->setMaxLength(70);
        $form->addPassword('password', 'Heslo')
            ->setRequired();
        $form->addSubmit('submit')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
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
            $this->userManager->add($values->name, $values->email, $values->password, $values->permissions);
            $this->presenter->flashMessage('Uživatel byl úspěšně přidán', 'success');
            $this->presenter->redirect('User:list');
        } catch (DuplicateNameException $e) {
            $form->addError('Uživatel s tímto jménem nebo emailem již existuje, prosím zvolte jiné jméno.');
        }
    }
}
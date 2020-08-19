<?php


namespace App\Forms\Admin\User;

use App\Model\DuplicateNameException;
use App\Model\UserManager;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

use stdClass;

class EditForm
{
    private UserManager $userManager;
    private Presenter $presenter;
    private string $userId;

    private ActiveRow $user;

    public function __construct(UserManager $userManager, Presenter $presenter, string $userId)
    {
        $this->userManager = $userManager;
        $this->presenter = $presenter;
        $this->userId = $userId;
    }

    public function create() {
        $form = new Form;
        $user = $this->userManager->getRepository()->findById($this->userId);

        $form->addText('name', 'Uživatel')
            ->setRequired()
            ->setDefaultValue($user->name)
            ->setMaxLength(25);
        $form->addEmail('email', 'Email')
            ->setRequired()
            ->setDefaultValue($user->email)
            ->setMaxLength(70);
        $form->addPassword('password', 'Heslo');
        $form->addSubmit('submit')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };

        $this->user = $user;
        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        try {
            if(empty($values->password)) $values->password = false;
            $this->userManager->edit($this->userId, $values->name, $values->email, $values->password);
            $this->presenter->flashMessage('Uživatel byl úspěšně změněn','success');
            $this->presenter->redirect('User:list');
        } catch (DuplicateNameException $e) {
            $form->addError('Uživatel s tímto jménem již existuje, prosím zvolte jiné jméno.');
        }
    }
}
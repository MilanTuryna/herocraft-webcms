<?php


namespace App\Forms\Panel\Main;


use App\Model\API\AuthMe;
use App\Model\Panel\AuthMeRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

/**
 * Class ChangePasswordForm
 * @package App\Forms\Panel\Main
 */
class ChangePasswordForm
{
    Private Presenter $presenter;
    Private ActiveRow $user;
    Private AuthMeRepository $authMeRepository;
    Private AuthMe $authMe;

    /**
     * ChangePasswordForm constructor.
     * @param Presenter $presenter
     * @param ActiveRow $user
     * @param AuthMeRepository $authMeRepository
     */
    public function __construct(Presenter $presenter, ActiveRow $user, AuthMeRepository $authMeRepository)
    {
        $this->presenter = $presenter;
        $this->user = $user;
        $this->authMeRepository = $authMeRepository;
        $this->authMe = new AuthMe();
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('old_password')
            ->setRequired();
        $form->addText('new_password')
            ->setRequired();
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     */
    public function success(Form $form, \stdClass $values) {
        if($values->old_password !== $values->new_password) {
            if($this->authMe->isValidPassword($values->old_password, $this->user->password)) {
                $hashedPassword = $this->authMe->hash($values->new_password);
                $this->authMeRepository->changePassword($hashedPassword, $this->user->id);
                $this->presenter->flashMessage('Heslo bylo úspěšně změněno!', 'success');
            } else {
                $form->addError('Heslo, které jsi zadal jako staré je nesprávné');
            }
        } else {
            $form->addError('Pokud si chceš změnit heslo, musíš zadat jiné, než to co momentálně máš.');
        }
    }
}
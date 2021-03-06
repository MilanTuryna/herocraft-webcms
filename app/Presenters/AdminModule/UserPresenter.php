<?php


namespace App\Presenters\AdminModule;

use App\Forms\Admin\User\CreateForm;
use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Model\UserManager;
use App\Model\UserRepository;
use App\Presenters\AdminBasePresenter;
use App\Forms\Admin\User\EditForm;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;

/**
 * Class UserPresenter
 * @package App\Presenters\AdminModule
 */
class UserPresenter extends AdminBasePresenter
{
    private UserRepository $userRepository;
    private UserManager $userManager;

    /**
     * UserPresenter constructor.
     * @param Authenticator $authenticator
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     */
    public function __construct(Authenticator $authenticator, UserManager $userManager, UserRepository $userRepository)
    {
        parent::__construct($authenticator, Permissions::ADMIN_FULL);

        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
    }

    public function renderList() {
        $this->template->users = $this->userRepository->findAll();
    }

    /**
     * @param $id
     */
    public function renderEdit($id) {
        $this->template->userId = $id;
    }

    /**
     * @param $id
     * @throws AbortException
     */
    public function actionDelete($id) {
        $deleted = $this->userRepository->deleteUser($id);
        if($deleted) {
            $this->flashMessage('Uživatel byl úspěšně smazán', 'success');
        } else {
            $this->flashMessage('Uživatel nemohl být smazán, jelikož neexistuje.', 'danger');
        }
        $this->redirect('User:list');
    }

    public function createComponentEditForm(): Multiplier {
        return new Multiplier(function(string $id) {
            return (new EditForm($this->userManager, $this, $id))->create();
        });
    }

    public function createComponentCreateForm(): Form {
        return (new CreateForm($this->userManager, $this))->create();
    }
}
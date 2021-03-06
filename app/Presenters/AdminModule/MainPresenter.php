<?php


namespace App\Presenters\AdminModule;

use App\Forms\Admin\SettingsForm; // __construct(Presenter, Explorer);
use App\Forms\Admin\UploadForm;
use App\Model\Admin\Roles\Permissions;
use App\Model\Admin\UploadManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\AdminBasePresenter;

use Nette;
use Nette\Database\Explorer;
use Nette\Application\UI\Form;
use Nette\Application\AbortException;

/**
 * Class AdminPresenter
 * @package App\Presenters
 */
class MainPresenter extends AdminBasePresenter
{
    use Nette\SmartObject;

    Private Explorer $db;
    Private SettingsRepository $settingsRepository;

    /**
     * AdminPresenter constructor.
     * @param Authenticator $authenticator
     * @param Explorer $db
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Authenticator $authenticator, Explorer $db, SettingsRepository $settingsRepository)
    {
        parent::__construct($authenticator);

        $this->db = $db;
        $this->settingsRepository = $settingsRepository;
    }

    /* přehledy, a nastavení webu */
    public function renderHome(): void {
        $this->template->web = $this->settingsRepository->getAllRows();
        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->stats = [
            "clanky" => $this->db->table('articles')->count('*'),
            "stranky" => $this->db->table('pages')->count('*'),
            "administrators" => $this->db->table('admin')->count('*'),
        ];
    }

    /**
     * @throws AbortException
     */
    public function renderSettings() {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_GLOBAL_SETTINGS)) {
            $this->template->logo = $this->settingsRepository->getLogo();
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_GLOBAL_SETTINGS) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @throws AbortException
     */
    public function renderUpload() {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_UPLOAD)) {
            $this->template->files = UploadManager::getUploads();
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_UPLOAD) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $file
     * @throws AbortException
     */
    public function actionRemoveUpload($file) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_UPLOAD)) {
            try {
                UploadManager::deleteUpload(str_replace('-', '.', $file));
                $this->flashMessage("Zadaný soubor byl úspěšně odstraněn.", "success");
            } catch (Nette\FileNotFoundException $exception) {
                $this->flashMessage("Zadaný soubor nemohl být odstraněn, jelikož neexistuje.", "danger");
            }

            $this->redirect("Main:upload");
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_UPLOAD) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @return Form
     */
    protected function createComponentUploadForm(): Form {
        return (new UploadForm($this))->create();
    }

    /**
     * @return Form
     */
    protected function createComponentSettingsForm(): Form
    {
        return (new SettingsForm($this, $this->settingsRepository))->create();
    }
}
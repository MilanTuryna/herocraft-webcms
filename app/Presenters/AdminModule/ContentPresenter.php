<?php


namespace App\Presenters\AdminModule;


use App\Forms\Admin\Content\Homepage\ChangeHeaderSectionForm;
use App\Forms\Content\Sections\CreateSectionForm;
use App\Forms\Content\Sections\EditSectionForm;
use App\Front\SectionRepository;
use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\SmartObject;

/**
 * Class ContentPresenter
 * @package App\Presenters\AdminModule
 */
final class ContentPresenter extends AdminBasePresenter
{
    use SmartObject;

    private SettingsRepository $settingsRepository;
    private SectionRepository $sectionRepository;

    /**
     * ContentPresenter constructor.
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param SectionRepository $sectionRepository
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator,
                                SettingsRepository $settingsRepository,
                                SectionRepository $sectionRepository,
                                string $permissionNode = Permissions::ADMIN_CONTENT_MANAGER)
    {
        parent::__construct($authenticator, $permissionNode);

        $this->settingsRepository = $settingsRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function renderOverview() {
        $this->template->sectionList = $this->sectionRepository::rowsToSectionList($this->sectionRepository->getAllSections());
    }

    /**
     * @param int $id
     * @param string|null $sectionName
     * @throws AbortException
     */
    public function actionDeleteSection(int $id, string $sectionName) {
        if($this->sectionRepository->deleteSection($id)) {
            $this->flashMessage("Sekce ".$sectionName." byla úspěšně odstraněna!", "success");
        } else {
            $this->flashMessage("Tato sekce nemohla být odstraněna, jelikož neexistuje!", "danger");
        }
        $this->redirect("Content:overview");
    }

    /**
     * @param int $id
     * @throws AbortException
     */
    public function renderEditSection(int $id) {
        $section = $this->sectionRepository->getSectionById($id);
        if($section) {
            $parsedSection = $this->sectionRepository::parseSection($section);
            if($parsedSection) {
                $this->template->section = $parsedSection;
            } else {
                $this->flashMessage("Nastala neznámá chyba při zpracovávání sekce z databáze (nikoliv SQL error!)", "danger");
                $this->redirect("Content:overview");
            }
        } else {
            $this->flashMessage("Nemůžeš editovat sekci, která neexistuje!");
            $this->redirect("Content:overview");
        }
    }

    /**
     * @return Form
     */
    public function createComponentChangeHeaderSectionForm(): Form {
        return (new ChangeHeaderSectionForm($this, $this->settingsRepository))->create();
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditSectionForm(): Multiplier {
        return new Multiplier(fn (string $sectionId): Form => (new EditSectionForm($this, $this->sectionRepository, (int)$sectionId, "this"))->create());
    }

    /**
     * @return Form
     */
    public function createComponentCreateSectionForm(): Form {
        return (new CreateSectionForm($this, $this->sectionRepository, $this->admin->getName()))->create();
    }
}
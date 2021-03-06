<?php


namespace App\Presenters\AdminModule;


use App\Forms\Admin\Content\Styles\Button\CreateButtonStyleForm;
use App\Forms\Admin\Content\Styles\Button\EditButtonStyleForm;
use App\Forms\Admin\Widgets\CreateWidgetForm;
use App\Forms\Admin\Content\Sections\CreateSectionForm;
use App\Forms\Admin\Content\Sections\EditSectionForm;
use App\Forms\Admin\Widgets\EditWidgetForm;
use App\Front\SectionRepository;
use App\Front\Styles\ButtonStyles;
use App\Front\WidgetRepository;
use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\SmartObject;
use Nette\Utils\Html;

/**
 * Class ContentPresenter
 * @package App\Presenters\AdminModule
 */
final class ContentPresenter extends AdminBasePresenter
{
    use SmartObject;

    private SettingsRepository $settingsRepository;
    private SectionRepository $sectionRepository;
    private ButtonStyles $buttonStyles;
    private WidgetRepository $widgetRepository;

    /**
     * ContentPresenter constructor.
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param SectionRepository $sectionRepository
     * @param ButtonStyles $buttonStyles
     * @param WidgetRepository $widgetRepository
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator,
                                SettingsRepository $settingsRepository,
                                SectionRepository $sectionRepository,
                                ButtonStyles $buttonStyles,
                                WidgetRepository $widgetRepository,
                                string $permissionNode = Permissions::ADMIN_CONTENT_MANAGER)
    {
        parent::__construct($authenticator, $permissionNode);

        $this->settingsRepository = $settingsRepository;
        $this->sectionRepository = $sectionRepository;
        $this->widgetRepository = $widgetRepository;
        $this->buttonStyles = $buttonStyles;
    }

    public function renderOverview() {
        $this->template->sectionList = $this->sectionRepository->rowsToSectionList($this->sectionRepository->getAllSections(), false);
        $this->template->widgetList = $this->widgetRepository->rowsToWidgetList($this->widgetRepository->getAllWidgets());
    }

    /**
     * @param int $id
     * @param string $widgetName
     * @throws AbortException
     */
    public function actionDeleteWidget(int $id, string $widgetName) {
        if($this->widgetRepository->deleteWidget($id)) {
            $this->flashMessage(Html::el()->addText("Widget ")
                ->addHtml(Html::el('strong')->setText($widgetName))
                ->addText(' byl úspěšně odstraněn!'), 'success');
        } else {
            $this->flashMessage("Tento widget nemohl být odstraněn, jelikož neexistuje!", "danger");
        }
        $this->redirect("Content:overview");
    }

    /**
     * @param int $id
     * @param string|null $sectionName
     * @throws AbortException
     */
    public function actionDeleteSection(int $id, string $sectionName) {
        if($this->sectionRepository->deleteSection($id)) {
            $this->flashMessage(Html::el()->addText("Sekce ")
                ->addHtml(Html::el('strong')->setText($sectionName))
                ->addText(' byla úspěšně odstraněna!'), "success");
        } else {
            $this->flashMessage("Tato sekce nemohla být odstraněna, jelikož neexistuje!", "danger");
        }
        $this->redirect("Content:overview");
    }

    /**
     * @param int $id
     * @throws AbortException
     */
    public function renderEditSection(int $id): void {
        $section = $this->sectionRepository->getSectionById($id);
        if($section) {
            $parsedSection = $this->sectionRepository->parseSection($section);
            if($parsedSection) {
                $this->template->section = $parsedSection;
            } else {
                $this->flashMessage("Nastala neznámá chyba při zpracovávání sekce z databáze (nikoliv SQL error!)", "danger");
                $this->redirect("Content:overview");
            }
        } else {
            $this->flashMessage("Nemůžeš editovat sekci, která neexistuje!", "danger");
            $this->redirect("Content:overview");
        }
    }

    /**
     * @param int $id
     * @throws AbortException
     */
    public function renderEditWidget(int $id): void {
        $widget = $this->widgetRepository->getWidgetById($id);
        if(!$widget) {
            $this->flashMessage("Nemůžeš editovat widget, který neexistuje!", "danger");
            $this->redirect("Content:overview");
        }
        $parsedWidget = $this->widgetRepository->parseWidget($widget);
        if(!$parsedWidget) {
            $this->flashMessage("Nastala neznámá chyba při zpracovávání sekce z databáze (nikoliv SQL error!)", "danger");
            $this->redirect("Content:overview");
        }
        $this->template->widget = $parsedWidget;
    }

    /**
     * @param int $id
     * @throws AbortException
     */
    public function renderEditButtonStyle(int $id): void {
        $buttonStyle = $this->buttonStyles->getStyleById($id);
        if($buttonStyle) {
            $this->template->style = $buttonStyle;
        } else {
            $this->flashMessage("Nemůžeš editovat styl tlačítka, který neexistuje", "danger");
            $this->redirect("Content:overview");
        }
    }

    /**
     * @param int $id
     * @param string $buttonStyleName
     * @throws AbortException
     */
    public function actionDeleteButtonStyle(int $id, string $buttonStyleName): void {
        if($this->buttonStyles->deleteStyle($id)) {
            $this->flashMessage(Html::el()
                ->addText('Styl tlačítka ')
                ->addHtml(Html::el('strong')
                    ->setText($buttonStyleName))
                ->addText(' byl úspěšně odstraněn!'), "success");
        } else {
            $this->flashMessage("Tento styl (se zadaným ID) nemohl být odstraněn, jelikož neexistuje!", "danger");
        }
        $this->redirect('Content:buttonStylesList');
    }

    public function renderButtonStylesList(): void {
        $this->template->buttonStyles = $this->buttonStyles->getStyles();
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditButtonStyleForm(): Multiplier {
        return new Multiplier(fn (string $styleId): Form => (new EditButtonStyleForm($this, $this->buttonStyles, $styleId))->create());
    }

    /**
     * @return Form
     */
    public function createComponentCreateButtonStyleForm(): Form {
        return (new CreateButtonStyleForm($this, $this->buttonStyles))->create();
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditSectionForm(): Multiplier {
        return new Multiplier(fn (string $sectionId): Form => (new EditSectionForm($this, $this->sectionRepository, $this->buttonStyles, (int)$sectionId, "this"))->create());
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditWidgetForm(): Multiplier {
        return new Multiplier(fn (string $widgetId): Form => (new EditWidgetForm($this, $this->widgetRepository, $widgetId, "this"))->create());
    }

    /**
     * @return Form
     */
    public function createComponentCreateSectionForm(): Form {
        return (new CreateSectionForm($this, $this->sectionRepository, $this->buttonStyles, $this->admin->getName(), "Content:overview"))->create();
    }

    public function createComponentCreateWidgetForm(): Form {
        return (new CreateWidgetForm($this, $this->widgetRepository, "Content:overview"))->create();
    }
}
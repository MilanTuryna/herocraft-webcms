<?php

namespace App\Forms\Admin\Widgets;

use App\Forms\Admin\Content\Widgets\Data\WidgetFormData;
use App\Front\WidgetRepository;
use App\Model\Front\UI\Parts\Widget;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\Html;

/**
 * Class CreateWidgetForm
 * @package App\Forms\Admin\Widgets
 */
class EditWidgetForm
{
    private Presenter $presenter;
    private WidgetRepository $widgetRepository;

    private int $widgetId;
    private Widget $parsedWidget;

    public string $afterRedirect;

    /**
     * CreateWidgetForm constructor.
     * @param Presenter $presenter
     * @param WidgetRepository $widgetRepository
     * @param int $widgetId
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, WidgetRepository $widgetRepository, int $widgetId, string $afterRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->widgetRepository = $widgetRepository;
        $this->afterRedirect = $afterRedirect;
        $this->widgetId = $widgetId;
        $this->parsedWidget = $this->widgetRepository->parseWidget($this->widgetRepository->getWidgetById($widgetId));
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Název widgetu')->setDefaultValue($this->parsedWidget->name)->setRequired(true);
        $form->addText('description', 'Popis widgetu')->setDefaultValue($this->parsedWidget->description)
            ->setRequired(false);
        $form->addTextArea('html', 'Obsah widgetu (HTML)')->setDefaultValue($this->parsedWidget->html)
            ->setRequired(false);
        $form->addSelect('side', 'Zařazení widgetu', WidgetFormData::SIDES)
            ->setDefaultValue($this->parsedWidget->side)
            ->setPrompt('Vyber zařazení widgetu')
            ->setRequired(true);
        $form->addSubmit('submit')->setRequired(true);
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param WidgetFormData $data
     * @throws AbortException
     */
    public function success(Form $form, WidgetFormData $data): void {
        $newWidget = $data->getWidget();
        $oldWidget = $this->parsedWidget;
        $oldWidget->dbId = null;
        if($oldWidget === $newWidget) {
            $this->presenter->flashMessage("Nebyly zaznamenány žádné změny.", "info");
            $this->presenter->redirect($this->afterRedirect);
        }
        if($this->widgetRepository->updateWidget($newWidget, $this->widgetId)) {
            $this->presenter->flashMessage(Html::el()
                ->addText('Widget s názvem ')
                ->addHtml(Html::el('strong')->setText($data->name))
                ->addText(' byl úspěšně aktualizován!'), 'success');
        } else {
            $form->addError('Widget nebyl aktualizován, nastala chyba při práci s databází!');
            $this->presenter->redirect($this->afterRedirect);
        }

        $this->presenter->redirect($this->afterRedirect);
    }
}
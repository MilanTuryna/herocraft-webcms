<?php

namespace App\Forms\Admin\Widgets;

use App\Forms\Admin\Content\Widgets\Data\WidgetFormData;
use App\Front\WidgetRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\Html;

/**
 * Class CreateWidgetForm
 * @package App\Forms\Admin\Widgets
 */
class CreateWidgetForm
{
    private Presenter $presenter;
    private WidgetRepository $widgetRepository;

    public string $afterRedirect;

    /**
     * CreateWidgetForm constructor.
     * @param Presenter $presenter
     * @param WidgetRepository $widgetRepository
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, WidgetRepository $widgetRepository, string $afterRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->widgetRepository = $widgetRepository;
        $this->afterRedirect = $afterRedirect;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Název widgetu')->setRequired(true);
        $form->addText('description', 'Popis widgetu')->setRequired(false);
        $form->addText('html', 'Obsah widgetu (HTML)')->setRequired(true);
        $form->addSelect('side', 'Zařazení widgetu', WidgetFormData::SIDES)
            ->setPrompt('Vyber zařazení widgetu')
            ->setRequired(true);
        return $form;
    }

    /**
     * @param Form $form
     * @param WidgetFormData $data
     * @throws AbortException
     */
    public function success(Form $form, WidgetFormData $data): void {
        $widget = $data->getWidget();
        if($this->widgetRepository->createWidget($widget)) {
            $this->presenter->flashMessage(Html::el()
                ->addText('Widget s názvem')
                ->addHtml(Html::el('strong')->setText($data->name))
                ->addText(' byl úspěšně vytvořen!'), 'success');
        } else {
            $form->addError('Widget nebyl vytvořen, nastala chyba při práci s databází!');
            $this->presenter->redirect($this->afterRedirect);
        }

        $this->presenter->redirect($this->afterRedirect);
    }
}
<?php

namespace App\Forms\Admin\Content\Homepage;

use App\Model\SettingsRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;
use stdClass;

/**
 * Class ChangeHeaderSectionForm
 * @package App\Forms\Admin\Content\Homepage
 */
class ChangeHeaderSectionForm
{
    use SmartObject;

    private Presenter $presenter;
    private SettingsRepository $settingsRepository;

    private string $afterRedirect = "this";

    /**
     * ChangeHeaderSectionForm constructor.
     * @param Presenter $presenter
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Presenter $presenter,
                                SettingsRepository $settingsRepository)
    {
        $this->presenter = $presenter;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @param string $afterRedirect
     */
    public function setAfterRedirect(string $afterRedirect): void
    {
        $this->afterRedirect = $afterRedirect;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('header2')->setRequired(false);
        $form->addText('header')->setRequired(true);
        $form->addText('text')->setRequired(false);
        $form->addSubmit('submit')->setRequired(true);
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
    public function success(Form $form, stdClass $values): void {
        if(str_contains($values->header2, "%IP%")) $values->header2 = str_replace("%IP%", $this->settingsRepository->getRow('ip'), $values->header2);
        if(false)
        {
            $this->presenter->flashMessage("Úvodní část webu byla úspěšně změněna!", "success");
        } else {
            $this->presenter->flashMessage("Při ukládání nových dat, došlo k chybě a úvodní část webu nebyla uložena.", "danger");
        }
        $this->presenter->redirect($this->afterRedirect);
    }
}
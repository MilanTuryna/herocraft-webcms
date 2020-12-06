<?php


namespace App\Forms\Minecraft;


use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;

/**
 * Class LuckPermsForm
 * @package App\Forms\Minecraft
 */
class FilterLuckPermsForm
{
    use SmartObject;

    private Presenter $presenter;

    /**
     * LuckPermsForm constructor.
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('player', 'Hledaný hráč')->setRequired(true);
        $form->addSubmit('submit', 'Odeslat')->setRequired(true);
        $form->onSuccess[] = [$this, 'success'];
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        $this->presenter->redirect("Minecraft:filterLuckPerms", $values->player);
    }
}
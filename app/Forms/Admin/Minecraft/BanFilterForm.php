<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use stdClass;

/**
 * Class BanFilterForm
 * @package App\Forms\Minecraft
 */
class BanFilterForm
{
    Private Bans $bans;
    Private Presenter $presenter;

    /**
     * BanFilterForm constructor.
     * @param Bans $bans
     * @param Presenter $presenter
     */
    public function __construct(Bans $bans, Presenter $presenter)
    {
        $this->bans = $bans;
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $form->addText('timeEnd', 'Do')->setHtmlType("date")->setRequired();
        $form->addText('timeStart', 'Od')->setHtmlType("date")->setRequired();
        $form->addText("players")->setRequired();
        $form->addSubmit('submit')->setRequired();

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     */
    public function success(Form $form, stdClass $values) {

    }
}
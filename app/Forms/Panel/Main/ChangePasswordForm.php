<?php


namespace App\Forms\Panel\Main;


use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class ChangePasswordForm
{
    Private Presenter $presenter;

    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('password')
            ->setRequired();
        $form->addSubmit('submit');
        return $form;
    }

    public function success(Form $form, \stdClass $values) {

    }
}
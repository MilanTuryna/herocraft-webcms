<?php


namespace App\Forms\Admin;


use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Nette\Application\UI\Form;

class WidgetEditForm
{
    Private Presenter $presenter;
    Private Context $context;

    public function __construct(Presenter $presenter, Context $context)
    {
        $this->presenter = $presenter;
        $this->context = $context;
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('widget')
            ->setRequired();
        $form->addSubmit('close');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    public function success(Form $form, \stdClass $values) {
        $this->context->table('widget')->wherePrimary(1)->update([
            'code' => $values
        ]);
        $this->presenter->flashMessage('HTML widget na hlavním webu byl úspěšně změněn.', 'success');
    }
}
<?php

namespace App\Forms\Admin\Social;

use App\Model\DynamicRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

class CreateForm
{
    private DynamicRepository $socialRepository;
    private Presenter $presenter;

    public function __construct(DynamicRepository $socialRepository, Presenter $presenter)
    {
        $this->socialRepository = $socialRepository;
        $this->presenter = $presenter;
    }

    public function create(): Form {
        $form = new Form;

        $form->addText('name', 'Název služby')->setRequired()->setMaxLength(30);
        $form->addText('link', 'URL sítě')
            ->setRequired()
            ->setMaxLength(128);
        $form->addText('color', 'Barva tlačítka')
            ->setMaxLength(6)
            ->setRequired();
        $form->addSubmit('submit')->setRequired();

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    public function success(Form $form, stdClass $values) {
        if(!$this->socialRepository->isDuplicated("name = ?", $values->name)) {
            $this->socialRepository->create([
                'name' => $values->name,
                'link' => $values->link,
                'color' => $values->color
            ]);
            $this->presenter->flashMessage('Sociální síť byla úspěšně přidána', 'success');
            $this->presenter->redirect('Social:list');
        } else {
            $form->addError('Sociální síť nemohla být přidána, jelikož stejný název již existuje.');
        }
    }
}
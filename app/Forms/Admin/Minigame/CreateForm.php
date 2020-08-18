<?php

namespace App\Forms\Admin\Minigame;

use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use App\Model\DynamicRepository;

use stdClass;

/**
 * Class CreateForm,
 */
class CreateForm
{
    private DynamicRepository $dynamicRepository;
    private Presenter $presenter;

    /**
     * CreateForm constructor.
     * @param Presenter $presenter
     * @param DynamicRepository $dynamicRepository
     */
    public function __construct(Presenter $presenter, DynamicRepository $dynamicRepository)
    {
        $this->presenter = $presenter;
        $this->dynamicRepository = $dynamicRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form
    {
        $form = new Form;
        $form->addText('name')
            ->setMaxLength(50)
            ->setRequired();
        $form->addText('description')
            ->setMaxLength(255)
            ->setRequired();
        $form->addSubmit('submit');
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
        if(!$this->dynamicRepository->isDuplicated('name = ?', $values->name)) {
            $this->dynamicRepository->create([
                'name' => $values->name,
                'description' => $values->description
            ]);
            $this->presenter->flashMessage('Minihra byla úspěšně přidána, pokud se neukázala v seznamu, refreshněte stránku!', 'success');
            $this->presenter->redirect('Minigame:list');
        } else {
            $form->addError('Minihra se stejným názvem již existuje, prosím zvolte jiný název.');
        }
    }
}
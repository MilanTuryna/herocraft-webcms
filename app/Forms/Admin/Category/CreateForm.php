<?php

namespace App\Forms\Admin\Category;

use App\Model\CategoryRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;

use stdClass;

/**
 * Class CreateForm,
 */
class CreateForm
{
    private CategoryRepository $categoryRepository;
    private Presenter $presenter;

    /**
     * CreateForm constructor.
     * @param Presenter $presenter
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(Presenter $presenter, CategoryRepository $categoryRepository)
    {
        $this->presenter = $presenter;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form
    {
        $form = new Form;
        $form->addText('name')
            ->setMaxLength(25)
            ->setRequired();
        $form->addText('color')
            ->setMaxLength(6)
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
        if(!$this->categoryRepository->isDuplicated($values->name)) {
            $this->categoryRepository->addCategory($values->name, $values->color);
            $this->presenter->flashMessage('Kategorie byla úspěšně přidána, pokud se neukázala v seznamu, refreshněte stránku!', 'success');
            $this->presenter->redirect('Category:list');
        } else {
            $form->addError('Kategorie se stejným názvem již existuje, prosím zvolte jiný název.');
        }
    }
}
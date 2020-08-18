<?php

namespace App\Forms\Admin\Category;

use App\Model\CategoryRepository;

use Nette\Application\AbortException;
use Nette\Database\Table\ActiveRow;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;

use stdClass;

/**
 * Class CreateForm,
 */
class EditForm
{
    private CategoryRepository $categoryRepository;
    private Presenter $presenter;
    private string $categoryId;

    private ActiveRow $category;

    /**
     * EditForm constructor.
     * @param Presenter $presenter
     * @param CategoryRepository $categoryRepository
     * @param string $categoryId
     */
    public function __construct(Presenter $presenter, CategoryRepository $categoryRepository, string $categoryId)
    {
        $this->presenter = $presenter;
        $this->categoryRepository = $categoryRepository;
        $this->categoryId = $categoryId;
    }

    /**
     * @return Form
     */
    public function create(): Form
    {
        $form = new Form;
        $category = $this->categoryRepository->findCategoryById($this->categoryId);

        $form->addText('name')
            ->setMaxLength(25)
            ->setDefaultValue($category->name)
            ->setRequired();
        $form->addText('color')
            ->setMaxLength(6)
            ->setDefaultValue($category->color)
            ->setRequired();
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        $this->category = $category;

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values): void {
        $duplicate = false;
        if($this->categoryRepository->isDuplicated($values->name) && trim($values->name) !== trim($this->category->name)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->categoryRepository->updateCategory($this->categoryId, [
                'name' => $values->name,
                'color' => $values->color
            ]);
            $this->presenter->flashMessage('Kategorie byla aktualizována', 'success');
            $this->presenter->redirect('Category:edit', $this->categoryId);
        } else {
            $form->addError('Kategorie se stejným názvem již existuje, prosím zvolte jiný název.');
        }
    }
}
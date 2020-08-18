<?php


namespace App\Forms\Admin\Minigame;

use App\Model\DynamicRepository;

use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Application\UI\Presenter;
use Nette\Application\AbortException;

use stdClass;

/**
 * Class EditForm
 * @package App\Forms\Admin\Minigame
 */
class EditForm
{
    private DynamicRepository $dynamicRepository;
    private Presenter $presenter;
    private string $minigameId;

    private ActiveRow $minigame;

    /**
     * EditForm constructor.
     * @param Presenter $presenter
     * @param DynamicRepository $dynamicRepository
     * @param string $minigameId
     */
    public function __construct(Presenter $presenter, DynamicRepository $dynamicRepository, string $minigameId)
    {
        $this->presenter = $presenter;
        $this->minigameId = $minigameId;
        $this->dynamicRepository = $dynamicRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form
    {
        $form = new Form;
        $minigame = $this->dynamicRepository->findById($this->minigameId);

        $form->addText('name')
            ->setMaxLength(50)
            ->setDefaultValue($minigame->name)
            ->setRequired();
        $form->addText('description')
            ->setMaxLength(255)
            ->setDefaultValue($minigame->description)
            ->setRequired();
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };

        $this->minigame = $minigame;

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values): void {
        $duplicate = false;
        if($this->dynamicRepository->isDuplicated('name = ?', $values->name) && trim($values->name) !== trim($this->minigame->name)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->dynamicRepository->update('id = ?', $this->minigameId, [
                'name' => $values->name,
                'description' => $values->description
            ]);
            $this->presenter->flashMessage('Změna byla aplikována.', 'success');
            $this->presenter->redirect('Minigame:edit', $this->minigameId);
        } else {
            $form->addError('Minihra se stejným názvem již existuje, prosím zvolte jiný název.');
        }
    }
}
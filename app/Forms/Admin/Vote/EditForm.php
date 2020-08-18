<?php


namespace App\Forms\Admin\Vote;

use App\Model\DynamicRepository;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

use stdClass;

/**
 * Class EditForm
 * @package App\Forms\Admin\Vote
 */
class EditForm
{
    private DynamicRepository $voteRepository;
    private Presenter $presenter;
    private string $voteId;

    private ActiveRow $vote;

    /**
     * EditForm constructor.
     * @param DynamicRepository $voteRepository
     * @param Presenter $presenter
     * @param string $voteId
     */
    public function __construct(DynamicRepository $voteRepository, Presenter $presenter, string $voteId)
    {
        $this->voteRepository = $voteRepository;
        $this->presenter = $presenter;
        $this->voteId = $voteId;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $vote = $this->voteRepository->findById($this->voteId);

        $form->addText('name', 'Název služby')
            ->setRequired()
            ->setDefaultValue($vote->name)
            ->setMaxLength(50);
        $form->addText('link', 'URL služby')
            ->setDefaultValue($vote->link)
            ->setRequired()
            ->setMaxLength(128);
        $form->addSubmit('submit')->setRequired();

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        $this->vote = $vote;

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values): void {
        $duplicate = false;
        if($this->voteRepository->isDuplicated('name = ?', $values->name) && trim($values->name) !== trim($this->vote->name)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->voteRepository->update('id = ?', $this->voteId, [
                'name' => $values->name,
                'link' => $values->link
            ]);
            $this->presenter->flashMessage('Změna byla aplikována.', 'success');
            $this->presenter->redirect('Vote:edit', $this->voteId);
        } else {
            $form->addError('Tento název služby již existuje, zvolte prosím jiný!');
        }
    }
}
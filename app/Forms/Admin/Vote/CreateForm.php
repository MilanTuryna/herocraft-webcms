<?php
namespace App\Forms\Admin\Vote;

use App\Model\DynamicRepository;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

class CreateForm
{
    private DynamicRepository $voteRepository;
    private Presenter $presenter;

    public function __construct(DynamicRepository $voteRepository, Presenter $presenter)
    {
        $this->voteRepository = $voteRepository;
        $this->presenter = $presenter;
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Název služby')->setRequired()->setMaxLength(50);
        $form->addText('link', 'URL služby')->setRequired()->setMaxLength(128);
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
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values) {
        if(!$this->voteRepository->isDuplicated('name = ?', $values->name)) {
            $this->voteRepository->create([
                'name' => $values->name,
                'link' => $values->link,
            ]);
            $this->presenter->flashMessage('Služba byla přidána!', 'success');
            $this->presenter->redirect('Vote:list');
        } else {
            $form->addError('Tento název služby již existuje, zvolte prosím jiný!');
        }
    }
}
<?php

namespace App\Forms\Panel\Friends;

use App\Model\API\Plugin\Friends;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class RemoveFriendForm
{
    private Presenter $presenter;
    private Friends $friends;
    private array $friendRelation;

    /**
     * RemoveFriendForm constructor.
     * @param Presenter $presenter
     * @param Friends $friends
     * @param array $friendRelation
     */
    public function __construct(Presenter $presenter, Friends $friends, array $friendRelation)
    {
        $this->presenter = $presenter;
        $this->friends = $friends;
        $this->friendRelation = $friendRelation;
    }

    public function create(): Form {
        $form = new Form;
        $form->addSubmit('submit', 'Delete');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        [$player, $friendId] = $this->friendRelation;
        $deleted = $this->friends->removeFriend($player, $friendId);
        if($deleted) {
            $this->presenter->flashMessage("Úspěšně jsi odstranil hráče (ID: {$friendId}) ze svých přátel.", 'success');
        } else {
            $form->addError('Tento hráč nemohl být odstraněn jelikož nebyl v seznamu tvých přátel.');
        }

        $this->presenter->redirect('Friends:list');
    }
}
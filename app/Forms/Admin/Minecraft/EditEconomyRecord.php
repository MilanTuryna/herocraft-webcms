<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\abstractIconomy;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class EditEconomyRecord
 * @package App\Forms\Minecraft
 */
class EditEconomyRecord
{
    private abstractIconomy $abstractIconomy;
    private Presenter $presenter;

    private int $recordId;
    private string $redirect;

    /**
     * EditEconomyRecord constructor.
     * @param abstractIconomy $abstractIconomy
     * @param Presenter $presenter
     * @param int $recordId
     */
    public function __construct(abstractIconomy $abstractIconomy, Presenter $presenter, int $recordId, string $redirect)
    {
        $this->abstractIconomy = $abstractIconomy;
        $this->presenter = $presenter;
        $this->recordId = $recordId;
        $this->redirect = $redirect;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $record = $this->abstractIconomy->getRowById($this->recordId)->fetch();

        $form->addText("username", "Hráč")->setRequired()->setDefaultValue($record->username);
        $form->addText("balance", "Obnos")->setRequired()->setDefaultValue($record->balance);
        $form->addSubmit('submit')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        $this->abstractIconomy->updateRowById($this->recordId, [
            "username" => $values->username,
            "balance" => $values->balance
        ]);
        $this->presenter->flashMessage("Záznam hráče " . $values->username . " byl úspěšně změněn a aktualizován.", "success");
        $this->presenter->redirect($this->redirect, $this->recordId);
    }
}
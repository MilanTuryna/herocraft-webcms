<?php

namespace App\Forms\Minecraft\Games;

use App\Model\API\Plugin\Games\Events;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class EditEventRecordForm
 * @package App\Forms\Minecraft
 */
class EditEventRecordForm
{
    private Events $events;
    private Presenter $presenter;

    private $recordId;

    /**
     * EditEventRecordForm constructor.
     * @param Events $events
     * @param Presenter $presenter
     * @param $recordId
     */
    public function __construct(Events $events, Presenter $presenter, $recordId)
    {
        $this->events = $events;
        $this->presenter = $presenter;
        $this->recordId = $recordId;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $record = $this->events->getPlayerById($this->recordId)->fetch();

        $form->addText('username', 'Nickname')->setDefaultValue($record->username)->setRequired();
        $form->addText('best_time', 'Čas')->setDefaultValue($record->best_time)->setRequired();
        $form->addText('event_passed', 'Úspěšné')->setDefaultValue($record->event_passed)->setRequired();
        $form->addText('event_giveup', 'Neúspěšné')->setDefaultValue($record->event_giveup)->setRequired();
        $form->addText('best_played', 'Nejlepší výsledek')->setDefaultValue($record->best_played)->setRequired();
        $form->addText('last_played', 'Poslední výsledek')->setDefaultValue($record->last_played)->setRequired();
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
     */
    public function success(Form $form, \stdClass $values) {
        $this->events->updateRecord($values, $this->recordId);
        $this->presenter->flashMessage("Záznam #" . $this->recordId . " (tento) byl úspěšně změněn!", "success");
    }
}
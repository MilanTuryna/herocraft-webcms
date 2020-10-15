<?php


namespace App\Presenters\AdminModule;


use App\Forms\Minecraft\EditEventRecordForm;
use App\Model\API\Plugin\Games\Events;
use App\Model\API\Plugin\Games\HeavySpleef;
use App\Model\API\Plugin\Games\HideAndSeek;
use App\Model\Security\Auth\Authenticator;

use App\Presenters\AdminBasePresenter;

use Nette\Application\AbortException;
use Nette\Application\UI\Multiplier;

/**
 * Class MinecraftGamesPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftGamesPresenter extends AdminBasePresenter
{
    Private Events $events;
    Private HideAndSeek $hideAndSeek;
    Private HeavySpleef $heavySpleef;

    public function __construct(Authenticator $authenticator, Events $events, HeavySpleef $heavySpleef, HideAndSeek $hideAndSeek)
    {
        parent::__construct($authenticator);

        $this->events = $events;
        $this->heavySpleef = $heavySpleef;
        $this->hideAndSeek = $hideAndSeek;
    }

    public function renderEventList() {
        $this->template->events = $this->events->findAllEvents()->fetchAll();
    }

    /**
     * @param $eventId
     * @throws AbortException
     */
    public function renderEvent($eventId) {
        $event = $this->events->getEventById($eventId)->fetch();
        if($event) {
            $this->template->event = $event;
            $this->template->eventPlayers = $this->events->getPlayersByEventId($eventId);
        } else {
            $this->redirect("MinecraftGames:eventList");
        }
    }

    /**
     * @param $recordId
     * @throws AbortException
     */
    public function renderEditEventRecord($recordId) {
        $record = $this->events->getPlayerById($recordId)->fetch(); // record (player)
        if($record) {
            $this->template->record = $record;
            $this->template->event = $this->events->getEventById($record->event_id)->fetch();
        } else {
            $this->redirect("MinecraftGames:eventList");
        }
    }

    /**
     * @param $recordId
     * @param $eventId
     * @throws AbortException
     */
    public function actionDeleteEventRecord(int $recordId, int $eventId = 0) {
        $deleted = $this->events->deleteRecordById($recordId);
        if($deleted) {
            $this->flashMessage("Záznam #" . $recordId . " byl úspešně odstraněn", 'success');
        } else {
            $this->flashMessage("Tento záznam nemohl být odstraněn, jelikož neexistuje!", 'danger');
        }
        if($eventId !== 0) {
            $this->redirect("MinecraftGames:event", $eventId);
        } else {
            $this->redirect("MinecraftGames:eventList");
        }
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditEventRecordForm(): Multiplier {
        return new Multiplier(function ($recordId) {
            return (new EditEventRecordForm($this->events, $this, $recordId))->create();
        });
    }
}
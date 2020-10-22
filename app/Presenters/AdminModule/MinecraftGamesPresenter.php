<?php


namespace App\Presenters\AdminModule;


use App\Forms\Minecraft\Games\EditEventRecordForm;
use App\Model\API\Plugin\Games\Events;
use App\Model\API\Plugin\Games\HideAndSeek;
use App\Model\API\Plugin\Games\SpleefX;
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
    private SpleefX $spleefX;

    public function __construct(Authenticator $authenticator, Events $events, SpleefX $spleefX, HideAndSeek $hideAndSeek)
    {
        parent::__construct($authenticator);

        $this->events = $events;
        $this->spleefX = $spleefX;
        $this->hideAndSeek = $hideAndSeek;
    }

    /*
     * EVENTS
     */

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
     * @param int $page
     * @throws AbortException
     */
    public function renderSpleefStats(int $page = 1) {
        $records = $this->spleefX->getAllRows();

        $lastPage = 0;
        $paginatorData = $records->page($page, 30, $lastPage);
        $this->template->records = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("MinecraftGames:spleefStats");
        }
    }

    /**
     * @param $playerUUID
     * @throws AbortException
     */
    public function renderEditSpleefRecord($playerUUID) {
        $record = $this->spleefX->getRowByUuid($playerUUID)->fetch();
        if($record) {
            $this->template->record = $record;
        } else {
            $this->flashMessage("Hráč se zadaným UUID neexistuje, jsi si jistý, že zadáváš správné?", "danger");
            $this->redirect("MinecraftGames:spleefStats");
        }
    }

    /*
     * HIDE AND SEEK
     */

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderHideAndSeekStats(int $page = 1) {
        $records = $this->hideAndSeek->getAllRows();

        $lastPage = 0;
        $paginatorData = $records->page($page, 30, $lastPage);
        $this->template->records = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("MinecraftGames:hideAndSeekStats");
        }
    }

    /**
     * @param $playerId
     * @throws AbortException
     */
    public function renderEditHASrecord($playerId) {
        $record = $this->hideAndSeek->getRowById($playerId);
        if($record) {
            $this->template->record = $record;
        } else {
            $this->flashMessage("Zadaný záznam neexistuje, nespletl jsi se?", 'danger');
            $this->redirect("MinecraftGames:hideAndSeekStats");
        }
    }

    /* COMPONENTY */

    /**
     * @return Multiplier
     */
    public function createComponentEditEventRecordForm(): Multiplier {
        return new Multiplier(function ($recordId) {
            return (new EditEventRecordForm($this->events, $this, $recordId))->create();
        });
    }
}
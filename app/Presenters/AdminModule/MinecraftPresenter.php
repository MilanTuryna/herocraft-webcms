<?php


namespace App\Presenters\AdminModule;


use App\Forms\Minecraft\ChatFilterForm;
use App\Forms\Minecraft\EditEventRecordForm;
use App\Model\API\Plugin\ChatLog;
use App\Model\API\Plugin\Events;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\Utils\Arrays;

class MinecraftPresenter extends AdminBasePresenter
{
    private ChatLog $chatLog;
    private Events $events;

    public function __construct(Authenticator $authenticator, ChatLog $chatLog, Events $events)
    {
        parent::__construct($authenticator);

        $this->chatLog = $chatLog;
        $this->events = $events;
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderChat(int $page = 1) {
        $messages = $this->chatLog->findAllRows();

        $lastPage = 0;
        $paginatorData = $messages->page($page, 150, $lastPage);
        $this->template->messages = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("Minecraft:chat");
        }
    }


    /**
     * @param $timeStart
     * @param $timeEnd
     * @param $players
     * @throws AbortException
     */
    public function renderFilterChat($timeStart, $timeEnd, array $players) {
        if($timeEnd && $timeStart && $players) {
            $messages = $this->chatLog->filterAllRows($players, $timeStart, $timeEnd)->fetchAll();
            if($messages) {
                $this->template->messages = $messages;
                $this->template->timeStart = $timeStart;
                $this->template->timeEnd = $timeEnd;
                $this->template->filteredPlayers = $players;
            } else {
                $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
                $this->redirect("Minecraft:chat");
            }
        } else {
            $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
            $this->redirect("Minecraft:chat");
        }
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
            $this->redirect("Minecraft:eventList");
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
            $this->redirect("Minecraft:eventList");
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

    /**
     * @return Form
     */
    public function createComponentChatFilterForm(): Form {
        return (new ChatFilterForm($this->chatLog, $this))->create();
    }
}
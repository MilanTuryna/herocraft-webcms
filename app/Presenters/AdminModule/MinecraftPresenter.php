<?php


namespace App\Presenters\AdminModule;


use App\Model\API\Plugin\ChatLog;
use App\Model\API\Plugin\Events;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;

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
}
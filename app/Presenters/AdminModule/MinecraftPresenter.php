<?php


namespace App\Presenters\AdminModule;


use App\Forms\Minecraft\BanFilterForm;
use App\Forms\Minecraft\ChatFilterForm;
use App\Forms\Minecraft\EditBanForm;
use App\Forms\Minecraft\EditEventRecordForm;

use App\Forms\Minecraft\EditIpBanForm;
use App\Forms\Minecraft\FilterForm;
use App\Model\API\Plugin\Bans;
use App\Model\API\Plugin\ChatLog;
use App\Model\API\Plugin\Events;
use App\Model\Security\Auth\Authenticator;

use App\Presenters\AdminBasePresenter;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;

/**
 * Class MinecraftPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftPresenter extends AdminBasePresenter
{
    private ChatLog $chatLog;
    private Events $events;
    private Bans $bans;

    /**
     * MinecraftPresenter constructor.
     * @param Authenticator $authenticator
     * @param ChatLog $chatLog
     * @param Events $events
     * @param Bans $bans
     */
    public function __construct(Authenticator $authenticator, ChatLog $chatLog, Events $events, Bans $bans)
    {
        parent::__construct($authenticator);

        $this->chatLog = $chatLog;
        $this->events = $events;
        $this->bans = $bans;
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

    /**
     * @param $timeStart
     * @param $timeEnd
     * @param array $players
     * @throws AbortException
     */
    public function renderFilterBan($timeStart, $timeEnd, array $players) {
        if($timeEnd && $timeStart && $players) {
            $bans = $this->bans->filterAllRows($players, $timeStart, $timeEnd)->fetchAll();
            if($bans) {
                $this->template->bans = $bans;
                $this->template->timeStart = $timeStart;
                $this->template->timeEnd = $timeEnd;
                $this->template->filteredPlayers = $players;
            } else {
                $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
                $this->redirect("Minecraft:banList");
            }
        } else {
            $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
            $this->redirect("Minecraft:banList");
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
     * @param int $page
     * @throws AbortException
     */
    public function renderBanList(int $page = 1) {
        $bans = $this->bans->getAllBans();

        $lastPage = 0;
        $paginatorData = $bans->page($page, 30, $lastPage);
        $this->template->bans = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("Minecraft:banList");
        }
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderIpBanList(int $page = 1) {
        $ipBans = $this->bans->getAllIPBans();

        $lastPage = 0;
        $paginatorData = $ipBans->page($page, 30, $lastPage);
        $this->template->ipBans = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("Minecraft:ipBanList");
        }
    }

    /**
     * @param $nick
     * @throws AbortException
     */
    public function renderEditBan($nick) {
        $ban = $this->bans->getBanByNick($nick)->fetch();
        if($ban) {
            $this->template->ban = $ban;
        } else {
            $this->flashMessage("Hráč " . $nick . " není zabanován!", "danger");
            $this->redirect("Minecraft:banList");
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
            $this->redirect("Minecraft:event", $eventId);
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
        return (new FilterForm($this, "Minecraft:filterChat"))->create();
    }

    /**
     * @return Form
     */
    public function createComponentBanFilterForm(): Form {
        return (new FilterForm($this, "Minecraft:filterBan"))->create();
    }

    /**
     * @return Form
     */
    public function createComponentIpBanFilterForm(): Form {
        return (new FilterForm($this, "Minecraft:filterIpBan"))->create();
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditBanForm(): Multiplier {
        return new Multiplier(function ($bannedPlayer) {
           return (new EditBanForm($this, $this->bans, $bannedPlayer))->create();
        });
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditIpBanForm(): Multiplier {
        return new Multiplier(function ($ip) {
            return (new EditIpBanForm($this->bans, $this, $ip))->create();
        });
    }
}
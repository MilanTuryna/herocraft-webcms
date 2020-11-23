<?php


namespace App\Presenters\AdminModule;

use App\Forms\Minecraft\EditBanForm;

use App\Forms\Minecraft\EditIpBanForm;
use App\Forms\Minecraft\FilterForm;
use App\Model\Admin\Roles\Permissions;
use App\Model\API\Plugin\Bans;
use App\Model\API\Plugin\ChatLog;
use App\Model\API\Plugin\LuckPerms;
use App\Model\API\Plugin\OnlinePlayers;
use App\Model\API\Plugin\PlayerTime;
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
    private Bans $bans;
    private LuckPerms $luckPerms;
    private OnlinePlayers $onlinePlayers;
    private PlayerTime $playerTime;

    /**
     * MinecraftPresenter constructor.
     * @param Authenticator $authenticator
     * @param ChatLog $chatLog
     * @param Bans $bans
     * @param LuckPerms $luckPerms
     * @param OnlinePlayers $onlinePlayers
     * @param PlayerTime $playerTime
     */
    public function __construct(Authenticator $authenticator,
                                ChatLog $chatLog,
                                Bans $bans,
                                LuckPerms $luckPerms,
                                OnlinePlayers $onlinePlayers,
                                PlayerTime $playerTime)
    {
        parent::__construct($authenticator);

        $this->chatLog = $chatLog;
        $this->bans = $bans;
        $this->luckPerms = $luckPerms;
        $this->onlinePlayers = $onlinePlayers;
        $this->playerTime = $playerTime;
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderChat(int $page = 1) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_CHATLOG)) {
            $messages = $this->chatLog->findAllRows();

            $lastPage = 0;
            $paginatorData = $messages->page($page, 150, $lastPage);
            $this->template->messages = $paginatorData;

            $this->template->page = $page;
            $this->template->lastPage = $lastPage;

            if($lastPage === 0) $this->template->page = 0;
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_CHATLOG) , 'danger');
            $this->redirect("Main:home");
        }
    }


    /**
     * @param $timeStart
     * @param $timeEnd
     * @param $players
     * @throws AbortException
     */
    public function renderFilterChat($timeStart, $timeEnd, array $players) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_CHATLOG)) {
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
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_CHATLOG) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $timeStart
     * @param $timeEnd
     * @param array $players
     * @throws AbortException
     */
    public function renderFilterBan($timeStart, $timeEnd, array $players) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_BANLIST)) {
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
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_BANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $nick
     * @throws AbortException
     */
    public function renderEditBan($nick) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_BANLIST)) {
            $ban = $this->bans->getBanByNick($nick)->fetch();
            if($ban) {
                $this->template->ban = $ban;
            } else {
                $this->flashMessage("Hráč " . $nick . " není zabanován!", "danger");
                $this->redirect("Minecraft:banList");
            }
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_BANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderBanList(int $page = 1) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_BANLIST)) {
            $bans = $this->bans->getAllBans();

            $lastPage = 0;
            $paginatorData = $bans->page($page, 30, $lastPage);
            $this->template->bans = $paginatorData;

            $this->template->page = $page;
            $this->template->lastPage = $lastPage;

            if($lastPage === 0) $this->template->page = 0;
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_BANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $timeStart
     * @param $timeEnd
     * @param array $ips
     * @throws AbortException
     */
    public function renderFilterIpBan($timeStart, $timeEnd, array $ips) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_IPBANLIST)) {
            if($timeEnd && $timeStart && $ips) {
                $ipBans = $this->bans->filterAllIpBans($ips, $timeStart, $timeEnd)->fetchAll();
                if($ipBans) {
                    $this->template->ipBans = $ipBans;
                    $this->template->timeStart = $timeStart;
                    $this->template->timeEnd = $timeEnd;
                    $this->template->filteredIps = $ips;
                } else {
                    $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
                    $this->redirect("Minecraft:ipBanList");
                }
            } else {
                $this->flashMessage("Bohuzel, data s timto filtrem, jsme nenasli.", "danger");
                $this->redirect("Minecraft:ipBanList");
            }
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_IPBANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderIpBanList(int $page = 1) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_IPBANLIST)) {
            $ipBans = $this->bans->getAllIPBans();

            $lastPage = 0;
            $paginatorData = $ipBans->page($page, 30, $lastPage);
            $this->template->ipBans = $paginatorData;

            $this->template->page = $page;
            $this->template->lastPage = $lastPage;

            if($lastPage === 0) $this->template->page = 0;
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_IPBANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @throws AbortException
     */
    public function renderHelpers() {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_HELPERS)) {
            $helpers = $this->luckPerms->getHelpers();
            $helpersPermissions = [];
            foreach ($helpers as $helper) {
                if(empty($helper->username)) break;
                if(!isset($helpersPermissions[$helper->username])) $helpersPermissions[$helper->username] = [];
                $data = new \stdClass();
                $data->permission = $helper->permission;
                $data->server = $helper->server;
                array_push($helpersPermissions[$helper->username], $data);
            }
            $this->template->helpers = $helpersPermissions;
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_HELPERS), 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $helper
     * @throws AbortException
     */
    public function renderHelperView($helper) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_HELPERS)) {
            $luckPerm = $this->luckPerms->getPlayerRows($helper);
            if($luckPerm) {
                $playerTimeWeek = $this->playerTime->getWeekPlayer($helper)->fetchAll();
                $playerTime = $this->playerTime->getRowByName($helper)->fetch();
                $this->template->luckPerm = $luckPerm;
                $this->template->playerTime = $playerTime;
                $this->template->playerTimeWeek = $playerTimeWeek;
            } else {
                $this->flashMessage("Tohoto helpera nemůžete rozklepnout, jelikož neexistuje", 'danger');
                $this->redirect("Minecraft:overview");
            }
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_HELPERS), 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @param $ip
     * @throws AbortException
     */
    public function renderEditIpBan($ip) {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_IPBANLIST)) {
            $ipBan = $this->bans->getIPBanByIP($ip)->fetch();
            if($ipBan) {
                $this->template->ipBan = $ipBan;
                $this->template->multiplierReplaceCharacter = Bans::MULTIPLIER_REPLACING_IP_CHARACTER;
            } else {
                $this->flashMessage("IP adresa " . $ip . " není zabanována!", "danger");
                $this->redirect("Minecraft:ipBanList");
            }
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_IPBANLIST) , 'danger');
            $this->redirect("Main:home");
        }
    }

    /**
     * @throws AbortException
     */
    public function renderOnlinePlayers() {
        if(Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_MC_IPBANLIST)) {
            $this->template->players = $this->onlinePlayers->getOnlinePlayers()->fetchAll();
        } else {
            $this->flashMessage(Permissions::getNoPermMessage(Permissions::ADMIN_MC_ONLINEPLAYERS) , 'danger');
            $this->redirect("Main:home");
        }
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
            return (new EditIpBanForm($this->bans, $this, str_replace(".", Bans::MULTIPLIER_REPLACING_IP_CHARACTER, $ip)))->create();
        });
    }
}
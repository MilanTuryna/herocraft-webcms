<?php


namespace App\Presenters\AdminModule;


use App\Model\Admin\Roles\Permissions;
use App\Model\API\Plugin\Classic\Economy;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Multiplier;
use App\Forms\Minecraft\EditEconomyRecord;

/**
 * Class MinecraftClassicPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftClassicPresenter extends AdminBasePresenter
{
    private Economy $classicEconomy;

    /**
     * MinecraftClassicPresenter constructor.
     * @param Authenticator $authenticator
     * @param Economy $classicEconomy
     */
    public function __construct(Authenticator $authenticator, Economy $classicEconomy)
    {
        parent::__construct($authenticator, Permissions::ADMIN_MC_CLASSIC);

        $this->classicEconomy = $classicEconomy;
    }

    /**
     * @param int $page
     */
    public function renderEconomy(int $page = 1) {
        $records = $this->classicEconomy->getAllRows();

        $lastPage = 0;
        $paginatorData = $records->page($page, 250, $lastPage);
        $this->template->records = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) {
            $this->template->page = 0;
        }
    }

    /**
     * @param $recordId
     * @throws AbortException
     */
    public function renderEditEconomyRecord($recordId) {
        $record = $this->classicEconomy->getRowById($recordId)->fetch();
        if($record) {
            $this->template->record = $record;
        } else {
            $this->flashMessage("Záznam, na který odkazuješ, pravděpodobně neexistuje.", "danger");
            $this->redirect("MinecraftClassic:economy");
        }
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditEconomyRecord(): Multiplier {
        return new Multiplier(function ($recordId) {
            return (new EditEconomyRecord($this->classicEconomy, $this, $recordId, "MinecraftClassic:editEconomyRecord"))->create();
        });
    }
}
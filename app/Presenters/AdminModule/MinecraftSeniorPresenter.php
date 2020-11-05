<?php


namespace App\Presenters\AdminModule;


use App\Forms\Minecraft\EditEconomyRecord;
use App\Model\Admin\Roles\Permissions;
use App\Model\API\Plugin\Senior\Economy;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Multiplier;

/**
 * Class MinecraftSeniorPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftSeniorPresenter extends AdminBasePresenter
{

    private Economy $economy;

    /**
     * MinecraftSeniorPresenter constructor.
     * @param Authenticator $authenticator
     * @param Economy $economy
     */
    public function __construct(Authenticator $authenticator, Economy $economy)
    {
        parent::__construct($authenticator, Permissions::ADMIN_MC_SENIOR);

        $this->economy = $economy;
    }

    /**
     * @param int $page
     */
    public function renderEconomy(int $page = 1) {
        $records = $this->economy->getAllRows();

        $lastPage = 0;
        $paginatorData = $records->page($page, 250, $lastPage);
        $this->template->records = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) $this->template->page = 0;
    }

    /**
     * @param $recordId
     * @throws AbortException
     */
    public function renderEditEconomyRecord($recordId) {
        $record = $this->economy->getRowById($recordId)->fetch();
        if($record) {
            $this->template->record = $record;
        } else {
            $this->flashMessage("Záznam, na který odkazuješ, pravděpodobně neexistuje.", "danger");
            $this->redirect("MinecraftSenior:economy");
        }
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditEconomyRecord(): Multiplier {
        return new Multiplier(function ($recordId) {
            return (new EditEconomyRecord($this->economy, $this, $recordId, "MinecraftSenior:editEconomyRecord"))->create();
        });
    }
}
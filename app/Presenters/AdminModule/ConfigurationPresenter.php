<?php


namespace App\Presenters\AdminModule;

use App\Model\Admin\Roles\Permissions;
use App\Model\API\CzechCraft;
use App\Model\DI\API;
use App\Model\DI\Configuration;
use App\Model\DI\Cron;
use App\Model\DI\GameSections;
use App\Model\DI\GoogleAnalytics;
use App\Model\DI\Tickets\Settings as TicketSettings;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;
use Nette\IOException;

/**
 * Class ConfigurationPresenter
 * @package App\Presenters\AdminModule
 */
final class ConfigurationPresenter extends AdminBasePresenter
{
    private Cron $cron;
    private GameSections $gameSections;
    private TicketSettings $ticketSettings;
    private GoogleAnalytics $googleAnalytics;
    private API $api;
    private CzechCraft $czechCraft;
    private Configuration $configuration;

    /**
     * ConfigurationPresenter constructor.
     * @param Cron $cron
     * @param Authenticator $authenticator
     * @param GameSections $gameSections
     * @param TicketSettings $ticketSettings
     * @param GoogleAnalytics $googleAnalytics
     * @param API $api
     * @param CzechCraft $czechCraft
     * @param Configuration $configuration
     */
    public function __construct(Cron $cron,
                                Authenticator $authenticator,
                                GameSections $gameSections,
                                TicketSettings $ticketSettings,
                                GoogleAnalytics $googleAnalytics,
                                API $api,
                                CzechCraft $czechCraft,
                                Configuration $configuration)
    {
        parent::__construct($authenticator, Permissions::ADMIN_FULL);

        $this->gameSections = $gameSections;
        $this->ticketSettings = $ticketSettings;
        $this->googleAnalytics = $googleAnalytics;
        $this->api = $api;
        $this->czechCraft = $czechCraft;
        $this->configuration = $configuration;
        $this->cron = $cron;
    }

    /**
     * @throws AbortException
     */
    public function actionUpdate() {
        try {
            $this->configuration->update();
            $this->flashMessage("Konfigurace byla úspěšně aktualizovaná.", "success");
        } catch (IOException $exception) {
            $this->flashMessage("Při resetování cache (mezipaměti) se něco pokazilo.", "danger");
        }
        $this->redirect("Configuration:overview");
    }

    public function renderOverview() {
        $this->template->gameSections = $this->gameSections->getSections();
        $this->template->ticketSettings = $this->ticketSettings;
        $this->template->googleAnalytics = $this->googleAnalytics;
        $this->template->api = $this->api;
        $this->template->czechCraftSlug = $this->czechCraft->getSlug();
        $this->template->cron = $this->cron;
    }
}
<?php


namespace App\Presenters;

use App\Model\DI\GoogleAnalytics;
use App\Model\SettingsRepository;

/**
 * Class PanelBasePresenter
 * @package App\Presenters
 */
abstract class PanelBasePresenter extends BasePresenter
{
    private SettingsRepository $settingsRepository;

    /**
     * PanelBasePresenter constructor.
     * @param SettingsRepository $settingsRepository
     * @param GoogleAnalytics $googleAnalytics
     */
    public function __construct(SettingsRepository $settingsRepository, GoogleAnalytics $googleAnalytics)
    {
        parent::__construct($googleAnalytics);

        $this->settingsRepository = $settingsRepository;
    }

    public function startup()
    {
        parent::startup();

        $nastaveni = $this->settingsRepository->getAllRows();
        $status = $this->settingsRepository->getStatus($nastaveni->ip);
        $this->template->nastaveni = $nastaveni;
        $this->template->status = !$nastaveni->udrzba ? $status->getCachedJson() : false; // pokud neni udrzba nebo api nefunguje
    }
}
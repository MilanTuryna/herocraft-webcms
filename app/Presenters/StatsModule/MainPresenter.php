<?php


namespace App\Presenters\StatsModule;


use App\Model\SettingsRepository;
use App\Presenters\BasePresenter;

class MainPresenter extends BasePresenter
{
    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        parent::__construct();

        $this->settingsRepository = $settingsRepository;
    }

    public function renderApp() {
        $this->template->nazev = $this->settingsRepository->getRow('nazev')->nazev;
    }
}
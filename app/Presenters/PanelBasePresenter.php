<?php


namespace App\Presenters;

use App\Model\Security\PluginAuthenticator;
use App\Model\SettingsRepository;

use Nette\Database\Table\ActiveRow;

class PanelBasePresenter extends BasePresenter
{
    private SettingsRepository $settingsRepository;
    private PluginAuthenticator $pluginAuthenticator;

    public function __construct(SettingsRepository $settingsRepository)
    {
        parent::__construct();
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
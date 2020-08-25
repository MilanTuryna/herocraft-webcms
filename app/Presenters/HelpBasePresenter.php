<?php


namespace App\Presenters;


use App\Model\SettingsRepository;

/**
 * Class HelpBasePresenter
 * @package App\Presenters
 */
class HelpBasePresenter extends BasePresenter
{
    private SettingsRepository $settingsRepository;

    /**
     * HelpBasePresenter constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        parent::__construct();

        $this->settingsRepository = $settingsRepository;
    }

    public function beforeRender()
    {
        $nastaveni = $this->settingsRepository->getAllRows();
        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->nastaveni = $nastaveni;
    }
}
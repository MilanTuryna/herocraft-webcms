<?php


namespace App\Presenters\StatsModule;


use App\Model\SettingsRepository;
use App\Model\Stats\CachedAPIRepository;
use App\Presenters\BasePresenter;
use App\Model\API\Status;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;

/**
 * Class MainPresenter
 * @package App\Presenters\StatsModule
 */
class MainPresenter extends BasePresenter
{
    private SettingsRepository $settingsRepository;
    private Cache $cache;
    private CachedAPIRepository $cachedAPIRepository;

    public function __construct(SettingsRepository $settingsRepository, IStorage $storage, CachedAPIRepository $cachedAPIRepository)
    {
        parent::__construct();

        $this->settingsRepository = $settingsRepository;
        $this->cache = new Cache($storage);
    }

    public function renderApp() {
        $nastaveni = $this->settingsRepository->getAllRows();

        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->nastaveni = $nastaveni;

        $status = new Status((string)$nastaveni->ip, $this->cache);
        $this->template->status = !$nastaveni->udrzba ? $status->getCachedJson() : null;

        $czechcraft = $this->cachedAPIRepository->getCzechCraftServer();

        $this->template->votesCount = $czechcraft->votes;
        $this->template->czechCraftOrder = $czechcraft->position;
        $this->template->votesLastMonth = '';
        $this->template->topVoter = $this->cachedAPIRepository->getTopVoters()[0];
    }
}
<?php


namespace App\Presenters;

use App\Front\WidgetRepository;
use App\Model\API\Status;
use App\Model\DI;
use App\Model\PageManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use Nette\Caching\Cache;

/**
 * Class FrontBasePresenter
 * @package App\Presenters
 */
abstract class FrontBasePresenter extends BasePresenter
{
    private Authenticator $authenticator;
    private SettingsRepository $settingsRepository;
    private PageManager $pageManager;
    private Cache $cache;
    private WidgetRepository $widgetRepository;

    /**
     * FrontBasePresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param Cache $cache
     * @param WidgetRepository $widgetRepository
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics, Authenticator $authenticator, SettingsRepository $settingsRepository, PageManager $pageManager, Cache $cache, WidgetRepository $widgetRepository)
    {
        parent::__construct($googleAnalytics);

        $this->authenticator = $authenticator;
        $this->settingsRepository = $settingsRepository;
        $this->pageManager = $pageManager;
        $this->cache = $cache;
        $this->widgetRepository = $widgetRepository;
    }

    public function beforeRender(): void
    {
        parent::beforeRender();

        $nastaveni = $this->settingsRepository->getAllRows();
        $status = new Status((string)$nastaveni->ip, $this->cache);
        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->widget = $this->settingsRepository->getWidgetCode(1);
        $this->template->nastaveni = $nastaveni;
        $this->template->pages = $this->pageManager->getPages();
        $this->template->status = $status->getCachedJson(); // pokud neni udrzba nebo api nefunguje, status se vypise jinak false
        $this->template->leftWidgets = $this->widgetRepository->rowsToWidgetList($this->widgetRepository->getLeftWidgets());
        $this->template->rightWidgets = $this->widgetRepository->rowsToWidgetList($this->widgetRepository->getRightWidgets());
    }
}
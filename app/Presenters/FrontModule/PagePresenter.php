<?php


namespace App\Presenters\FrontModule;


use App\Front\WidgetRepository;
use App\Model\ArticleRepository;
use App\Model\DI;
use App\Model\PageManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\FrontBasePresenter;
use Nette\Application\BadRequestException;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

class PagePresenter extends FrontBasePresenter
{
    private PageManager $pageManager;
    private ArticleRepository $articleRepository;

    /**
     * PagePresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param Storage $storage
     * @param ArticleRepository $articleRepository
     * @param WidgetRepository $widgetRepository
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics, Authenticator $authenticator, SettingsRepository $settingsRepository, PageManager $pageManager,
                                Storage $storage, ArticleRepository $articleRepository, WidgetRepository $widgetRepository)
    {
        parent::__construct($googleAnalytics, $authenticator, $settingsRepository, $pageManager, new Cache($storage), $widgetRepository);

        $this->pageManager = $pageManager;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param string $pageUrl
     * @throws BadRequestException
     */
    public function renderView(string $pageUrl) {
        $pageObj = $this->pageManager->getPage($pageUrl);
        if($pageObj) {
            $this->template->page = $pageObj;
            $this->template->sidebarArticles = $this->articleRepository->findArticlesWithCategory(5);
        } else {
            $this->error($this->translator->translate("front.flashMessages.pageNotFound"));
        }
    }
}
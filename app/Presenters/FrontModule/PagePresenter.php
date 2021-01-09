<?php

declare(strict_types=1);

namespace App\Presenters\FrontModule;

use App\Front\SectionRepository;
use App\Model\DI\GameSections;
use App\Model\DI\GoogleAnalytics;
use App\Model\Security\Auth\Authenticator;
use App\Model\Stats\CachedAPIRepository;
use App\Model\SettingsRepository;
use App\Model\ArticleRepository;
use App\Model\CategoryRepository;
use App\Model\PageManager;
use App\Model\API\Status;

use App\Presenters\BasePresenter;

use Nette;
use Nette\Caching;

use Exception;

/**
 * Class HomepagePresenter
 * @package App\Presenters
 */
final class PagePresenter extends BasePresenter
{
    use Nette\SmartObject;

    private PageManager $pageManager;
    private Caching\Cache $cache;
    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private Authenticator $authenticator;
    private SettingsRepository $settingsRepository;
    private GameSections $gameSections;
    private CachedAPIRepository $cachedAPIRepository;
    private SectionRepository $sectionRepository;

    /**
     * PagePresenter constructor.
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param Caching\IStorage $storage
     * @param Authenticator $authenticator
     * @param GoogleAnalytics $googleAnalytics
     * @param GameSections $gameSections
     * @param CachedAPIRepository $cachedAPIRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(ArticleRepository $articleRepository,
                                CategoryRepository $categoryRepository,
                                SettingsRepository $settingsRepository,
                                PageManager $pageManager,
                                Caching\IStorage $storage,
                                Authenticator $authenticator,
                                GoogleAnalytics $googleAnalytics,
                                GameSections $gameSections,
                                CachedAPIRepository $cachedAPIRepository, SectionRepository $sectionRepository)
    {
        parent::__construct($googleAnalytics);

        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cache = new Caching\Cache($storage);
        $this->pageManager = $pageManager;
        $this->settingsRepository = $settingsRepository;
        $this->authenticator = $authenticator;
        $this->gameSections = $gameSections;
        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function startup(): void {
        parent::startup();

        $nastaveni = $this->settingsRepository->getAllRows();

        $status = new Status((string)$nastaveni->ip, $this->cache);

        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->widget = $this->settingsRepository->getWidgetCode(1);
        $this->template->nastaveni = $nastaveni;
        $this->template->categoryRepository = $this->categoryRepository;
        $this->template->articleRepository = $this->articleRepository;
        $this->template->stranky = $this->pageManager->getPages();
        $this->template->status = $status->getCachedJson(); // pokud neni udrzba nebo api nefunguje, status se vypise jinak false
    }

    /**
     * @throws Exception
     */
    public function renderHome(): void {
        $articles = $this->articleRepository->findArticlesWithCategory(6)->fetchAll();
        $articlesArr = [];

        foreach ($articles as $article) array_push($articlesArr, $article);
        $this->template->articlesCount = $this->articleRepository->getPublishedArticlesCount();
        $this->template->articles = $articlesArr;
        $this->template->gameSections = $this->gameSections;
        $this->template->registerCount = $this->cachedAPIRepository->getRegisterCount();
        $this->template->timesPlayed = $this->cachedAPIRepository->getTimesPlayed();
        $this->template->sectionList = $this->sectionRepository::rowsToSectionList($this->sectionRepository->getAllSections());
    }

    /**
     * @param string $page
     * @throws Nette\Application\BadRequestException
     */
    public function renderPage(string $page): void {
        $pageObj = $this->pageManager->getPage($page);
        if($pageObj) {
            $this->template->page = $pageObj;
        } else {
            $this->error($this->translator->translate("front.flashMessages.pageNotFound"));
        }
    }

    /**
     * @param int $page
     * @throws Exception
     */
    public function renderArchiv(int $page = 1): void // list článků
    {
        $articlesCount = $this->articleRepository->getPublishedArticlesCount();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount); // celkový počet článků
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        $this->template->paginator = $paginator;

        $articles = $this->articleRepository->findArticlesWithCategory($paginator->getLength(), $paginator->getOffset());
        $this->template->articles = $articles;

        $lastPage = $paginator->getLastPage();

        $this->template->logged = (bool)$this->authenticator->getUser();

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) $this->template->page = 0;
    }

    /**
     * @param $articleUrl
     * @throws Nette\Application\BadRequestException
     */
    public function renderArticle($articleUrl): void {
        $article = $this->articleRepository->findArticleByUrl($articleUrl);
        if($article) {
            $this->template->logged = (bool)$this->authenticator->getUser();
            $this->template->article = $article;
            $this->template->category = $article->ref('categories', 'category_id', 'color', 'name');
        } else {
            $this->error(
                $this->translator->translate("front.flashMessages.articleNotFound")
            );
        }
    }
}

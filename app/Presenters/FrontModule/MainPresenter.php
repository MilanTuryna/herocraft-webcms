<?php


namespace App\Presenters\FrontModule;


use App\Forms\Panel\SignInForm;
use App\Front\SectionRepository;
use App\Front\WidgetRepository;
use App\Model\ArticleRepository;
use App\Model\DI;
use App\Model\PageManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\Security\Auth\PluginAuthenticator;
use App\Model\SettingsRepository;
use App\Model\Stats\CachedAPIRepository;
use App\Presenters\FrontBasePresenter;
use Exception;
use Nette\Application\UI\Form;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Utils\Paginator;
use Throwable;

/**
 * Class MainPresenter
 * @package App\Presenters\FrontModule
 */
class MainPresenter extends FrontBasePresenter
{
    private Authenticator $authenticator;
    private PluginAuthenticator $pluginAuthenticator;
    private ArticleRepository $articleRepository;
    private CachedAPIRepository $cachedAPIRepository;
    private SectionRepository $sectionRepository;
    private DI\GameSections $gameSections;

    /**
     * LandingPresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param Storage $storage
     * @param PluginAuthenticator $pluginAuthenticator
     * @param ArticleRepository $articleRepository
     * @param CachedAPIRepository $cachedAPIRepository
     * @param SectionRepository $sectionRepository
     * @param DI\GameSections $gameSections
     * @param WidgetRepository $widgetRepository
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics,
                                Authenticator $authenticator,
                                SettingsRepository $settingsRepository,
                                PageManager $pageManager,
                                Storage $storage,
                                PluginAuthenticator $pluginAuthenticator,
                                ArticleRepository $articleRepository,
                                CachedAPIRepository $cachedAPIRepository,
                                SectionRepository $sectionRepository,
                                DI\GameSections $gameSections,
                                WidgetRepository $widgetRepository)
    {
        parent::__construct($googleAnalytics, $authenticator, $settingsRepository, $pageManager, new Cache($storage), $widgetRepository);

        $this->authenticator = $authenticator;
        $this->pluginAuthenticator = $pluginAuthenticator;
        $this->articleRepository = $articleRepository;
        $this->cachedAPIRepository = $cachedAPIRepository;
        $this->sectionRepository = $sectionRepository;
        $this->gameSections = $gameSections;
    }

    /**
     * @throws Throwable
     */
    public function renderLandingPage() {
        $articles = $this->articleRepository->findArticlesWithCategory(11)->fetchAll();
        $articlesArr = [];
        foreach ($articles as $article) $articlesArr[] = $article; //array_push($articlesArr, $article);
        $this->template->articlesCount = $this->articleRepository->getPublishedArticlesCount();
        $this->template->articles = $articlesArr;
        $this->template->gameSections = $this->gameSections;
        $this->template->registerCount = $this->cachedAPIRepository->getRegisterCount();
        $this->template->timesPlayed = $this->cachedAPIRepository->getTimesPlayed();
        $this->template->sectionList = $this->sectionRepository->rowsToSectionList($this->sectionRepository->getAllSections());
    }

    /**
     * @param int $pagination
     * @throws Exception
     */
    public function renderArchive(int $pagination = 1) {
        $articlesCount = $this->articleRepository->getPublishedArticlesCount();

        $paginator = new Paginator;
        $paginator->setItemCount($articlesCount); // celkový počet článků
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($pagination); // číslo aktuální stránky

        $this->template->paginator = $paginator;

        $articles = $this->articleRepository->findArticlesWithCategory($paginator->getLength(), $paginator->getOffset());
        $this->template->articles = $articles;

        $lastPage = $paginator->getLastPage();

        $this->template->logged = (bool)$this->authenticator->getUser();

        $this->template->page = $pagination;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) $this->template->page = 0;
    }

    /**
     * @return Form
     */
    public function createComponentSignInPanelForm(): Form {
        return (new SignInForm($this->pluginAuthenticator, $this, null, true))->create();
    }
}
<?php

declare(strict_types=1);

namespace App\Presenters\FrontModule;

use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\BasePresenter;

use App\Model\ArticleRepository;
use App\Model\CategoryRepository;
use App\Model\PageManager;
use App\Model\API\Status;

use Exception;
use Nette;
use Nette\Database\Context;
use Nette\Caching;


/**
 * Class HomepagePresenter
 * @package App\Presenters
 */
final class PagePresenter extends BasePresenter
{
    use Nette\SmartObject;

    private Nette\Database\Context $db;
    private PageManager $pageManager;
    private Caching\Cache $cache;
    private Nette\Http\Response $http;
    private ArticleRepository $articleRepository;
    private CategoryRepository $categoryRepository;
    private Authenticator $authenticator;
    private SettingsRepository $settingsRepository;

    /**
     * PagePresenter constructor.
     * @param Context $db
     * @param Caching\IStorage $storage
     * @param Nette\Http\Response $http
     * @param Authenticator $authenticator
     */
    public function __construct(Nette\Database\Context $db, Caching\IStorage $storage, Nette\Http\Response $http, Authenticator $authenticator)
    {
        parent::__construct();

        $this->http = $http;
        $this->db = $db;
        $this->articleRepository = new ArticleRepository($db);
        $this->categoryRepository = new CategoryRepository($db);
        $this->cache = new Caching\Cache($storage);
        $this->pageManager = new PageManager($db);
        $this->settingsRepository = new SettingsRepository($db, $storage);
        $this->authenticator = $authenticator;
    }

    public function startup(): void {
        parent::startup();

        $nastaveni = $this->db->table('nastaveni')->get(1);

        $status = new Status((string)$nastaveni->ip, $this->cache);

        $this->template->logo = $this->settingsRepository->getLogo();
        $this->template->widget = $this->db->table('widget')->wherePrimary(1)->fetch();
        $this->template->nastaveni = $nastaveni;
        $this->template->categoryRepository = $this->categoryRepository;
        $this->template->articleRepository = $this->articleRepository;
        $this->template->stranky = $this->db->table('pages');
        $this->template->status = $status->getCachedJson(); // pokud neni udrzba nebo api nefunguje, status se vypise jinak false
    }

    /**
     * @throws Exception
     */
    public function renderHome(): void {
        $articles = $this->articleRepository->findArticlesWithCategory(6)->fetchAll();
        $articlesArr = [];

        foreach ($articles as $article) array_push($articlesArr, $article);

        $this->template->articles = $articlesArr;
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
            $this->error('Tato stránka nebyla nalezena!');
        }
    }

    /**
     * @param int $page
     * @throws Nette\Application\AbortException
     * @throws Exception
     */
    public function renderArchiv(int $page = 1): void // list článků
    {
        $articles = $this->articleRepository->findPublishedArticles();
        $lastPage = 0;

        $this->template->articles = $articles->page($page, ArticleRepository::PROPERTIES['per_page'], $lastPage);
        $this->template->articleProperties = ArticleRepository::PROPERTIES;
        $this->template->logged = (bool)$this->authenticator->getUser();

        if($page > $lastPage) {
            $this->redirect('Page:archiv');
        }

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;
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
            $this->error('Tento článek neexistuje!');
        }
    }
}

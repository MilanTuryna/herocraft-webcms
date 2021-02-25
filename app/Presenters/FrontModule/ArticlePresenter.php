<?php


namespace App\Presenters\FrontModule;


use App\Model\ArticleRepository;
use App\Model\DI;
use App\Model\PageManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Model\Stats\CachedAPIRepository;
use App\Model\Utils;
use App\Presenters\FrontBasePresenter;
use Contributte\PdfResponse\PdfResponse;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

/**
 * Class ArticlePresenter
 * @package App\Presenters\FrontModule
 */
class ArticlePresenter extends FrontBasePresenter
{
    private SettingsRepository $settingsRepository;
    private ArticleRepository $articleRepository;
    private CachedAPIRepository $cachedAPIRepository;

    /**
     * ArticlePresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param ArticleRepository $articleRepository
     * @param CachedAPIRepository $cachedAPIRepository
     * @param Storage $storage
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics, Authenticator $authenticator, SettingsRepository $settingsRepository, PageManager $pageManager,
                                ArticleRepository $articleRepository, CachedAPIRepository $cachedAPIRepository, Storage $storage)
    {
        parent::__construct($googleAnalytics, $authenticator, $settingsRepository, $pageManager, new Cache($storage));

        $this->settingsRepository = $settingsRepository;
        $this->articleRepository = $articleRepository;
        $this->cachedAPIRepository = $cachedAPIRepository;
    }

    /**
     * @param string $articleUrl
     * @throws BadRequestException
     */
    public function renderView(string $articleUrl) {
        $article = $this->articleRepository->findArticleByUrl($articleUrl);
        if($article) {
            $this->template->article = $article;
            $this->template->articles = $this->articleRepository->findArticlesWithCategory(6);
        } else {
            $this->error(
                $this->translator->translate("front.flashMessages.articleNotFound")
            );
        }
    }

    /**
     * @param string $articleUrl
     * @param bool $download
     * @throws AbortException
     */
    public function actionExport(string $articleUrl, bool $download) {
        $article = $this->articleRepository->findArticleByUrl($articleUrl);

        $webName = $this->settingsRepository->getRow('nazev')->nazev;

        $this->template->webName = $webName;
        $this->template->articleName = $article->name;
        $this->template->articleId = $article->id;
        $this->template->articleContent = $article->content;
        $this->template->articleRow = $article;

        try {$this->sendTemplate(); } catch (AbortException $d) {}

        $response = new PdfResponse($this->getTemplate());
        $response->setDocumentAuthor($webName);
        $response->setPageFormat('A4-L');
        $response->setDocumentTitle(Utils::parseURL($webName) . '-clanek' . $article->id);
        $response->setSaveMode($download ? PdfResponse::DOWNLOAD : PdfResponse::INLINE);
        $this->sendResponse($response);
    }
}
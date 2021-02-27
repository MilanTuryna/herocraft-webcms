<?php


namespace App\Presenters\FrontModule;


use App\Front\WidgetRepository;
use App\Model\ArticleRepository;
use App\Model\DI;
use App\Model\PageManager;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Model\Stats\CachedAPIRepository;
use App\Model\Utils;
use App\Presenters\FrontBasePresenter;
use Contributte\PdfResponse\PdfResponse;
use Mpdf\HTMLParserMode;
use Mpdf\MpdfException;
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
    private Cache $cache;

    public array $exportStylesheets = [];

    /**
     * ArticlePresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param PageManager $pageManager
     * @param ArticleRepository $articleRepository
     * @param CachedAPIRepository $cachedAPIRepository
     * @param Storage $storage
     * @param WidgetRepository $widgetRepository
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics, Authenticator $authenticator, SettingsRepository $settingsRepository, PageManager $pageManager,
                                ArticleRepository $articleRepository, CachedAPIRepository $cachedAPIRepository, Storage $storage, WidgetRepository $widgetRepository)
    {
        $this->cache = new Cache($storage);
        parent::__construct($googleAnalytics, $authenticator, $settingsRepository, $pageManager, $this->cache, $widgetRepository);

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
     * @throws MpdfException
     */
    public function actionExport(string $articleUrl, bool $download) {
        $article = $this->articleRepository->findArticleByUrl($articleUrl);

        $webName = $this->settingsRepository->getRow('nazev')->nazev;

        $this->template->webName = $webName;
        $this->template->articleName = $article->name;
        $this->template->articleUrl = $article->url;
        $this->template->articleId = $article->id;
        $this->template->articleCategoryID = $article->category_id;
        $this->template->articleCreated = $article->created_at;
        $this->template->articleContent = $article->content;

        try {$this->sendTemplate(); } catch (AbortException $d) {}

        $response = new PdfResponse($this->getTemplate());
        $response->setDocumentAuthor($webName);
        $response->setPageFormat('A4-L');
        $response->setDocumentTitle(Utils::parseURL($webName) . '-clanek' . $article->id);
        $response->setSaveMode($download ? PdfResponse::DOWNLOAD : PdfResponse::INLINE);
        $mPdf = $response->getMPDF();
        foreach($this->exportStylesheets as $sty) $mPdf->WriteHTML(file_get_contents($sty), HTMLParserMode::HEADER_CSS);
        $this->sendResponse($response);
    }
}
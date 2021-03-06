<?php


namespace App\Presenters\AdminModule;

use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Exception;

use App\Forms\Admin\Article\CreateForm;
use App\Model\ArticleRepository;

use App\Forms\Admin\Article\EditForm;

use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nette\Forms\Form;


/**
 * Class UserPresenter
 * @package App\Presenters\AdminModule
 */
class ArticlePresenter extends AdminBasePresenter
{
    private ArticleRepository $articleRepository;

    /**
     * ArticlePresenter constructor.
     * @param Authenticator $authenticator
     * @param ArticleRepository $articleRepository
     */
    public function __construct(Authenticator $authenticator, ArticleRepository $articleRepository)
    {
        parent::__construct($authenticator, Permissions::ADMIN_ARTICLES);

        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws Exception
     */
    public function renderList(): void {
        $articles = $this->articleRepository->findPublishedArticles();
        $this->template->articles = $articles;
    }

    /**
     * @param $url
     * @throws BadRequestException
     */
    public function renderEdit($url): void {
        $article = $this->articleRepository->findArticleByUrl($url);
        $this->template->miniature = $this->articleRepository->getMiniature($article->id);

        if($article) {
            $this->template->article = $article;
        } else {
            $this->error('Tento článek neexistuje!');
        }
    }

    /**
     * @param $url
     * @throws AbortException
     */
    public function actionDelete($url) {
        $deleted = $this->articleRepository->deleteArticle($url);
        if($deleted) {
            $this->flashMessage('Článek byl úspěšně odstraněn!', 'success');
        } else {
            $this->flashMessage('Článek nebyl odstraněn, jelikož pravděpodobně neexistuje', 'danger');
        }
        $this->redirect('Article:list');
    }

    /**
     * @return Multiplier
     */
    protected function createComponentEditForm(): Multiplier
    {
        /*
         * Multiplier kvůli tomu, abychom mohli v šabloně předat data (nette, dynamická komponenta)
         */
        return new Multiplier(function(string $articleId) {
            return (new EditForm($this, $this->articleRepository, $articleId))->create();
        });
    }

    protected function createComponentCreateForm(): Form {
        return (new CreateForm($this, $this->articleRepository, $this->admin))->create();
    }
}
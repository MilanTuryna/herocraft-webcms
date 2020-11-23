<?php


namespace App\Presenters\AdminModule;

use App\Forms\Admin\Pages\CreateForm;
use App\Model\Admin\Roles\Permissions;
use App\Model\PageRepository;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use App\Forms\Admin\Pages\EditForm;

use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nette\Forms\Form;

/**
 * Class PagePresenter
 * @package App\Presenters\AdminModule
 */
class PagePresenter extends AdminBasePresenter
{
    private PageRepository $pageRepository;

    /**
     * PagePresenter constructor.
     * @param Authenticator $authenticator
     * @param PageRepository $pageRepository
     */
    public function __construct(Authenticator $authenticator, PageRepository $pageRepository)
    {
        parent::__construct($authenticator, Permissions::ADMIN_PAGES);

        $this->pageRepository = $pageRepository;
    }

    public function renderList(): void {
        $this->template->pages = $this->pageRepository->findPages();
    }

    /**
     * @param string $url
     * @throws AbortException
     */
    public function renderEdit(string $url): void {
        $page = $this->pageRepository->findPageByUrl($url);
        if($page) {
            $this->template->page = $page;
        } else {
            $this->flashMessage('Stránka, na kterou jste odkazovali, nemůže být odstraněna, jelikož neexistuje', 'danger');
            $this->redirect("Page:list");
        }
    }

    /**
     * @param $url
     * @throws AbortException
     */
    public function actionDelete($url): void {
        $deleted = $this->pageRepository->deletePage($url);
        if($deleted) {
            $this->flashMessage('Článek byl úspěšně odstraněn!', 'success');
        } else {
            $this->flashMessage('Tato stránka nebyla odstraněna, pravděpodobně neexistovala.', 'danger');
        }

        $this->redirect('Page:list');
    }

    /**
     * @return Multiplier
     */
    public function createComponentEditForm(): Multiplier {
        return new Multiplier(fn(string $pageId) => (new EditForm($this, $this->pageRepository, $pageId))->create());
    }

    /**
     * @return Form
     */
    public function createComponentCreateForm(): Form {
        return (new CreateForm($this, $this->pageRepository))->create();
    }
}
<?php

namespace App\Forms\Admin\Pages;

use App;
use App\Model\Utils;
use App\Model\PageRepository;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

/**
 * Class EditForm
 * @package App\Forms\Admin\Pages
 */
class EditForm
{
    use Nette\SmartObject;

    private App\Model\PageRepository $pageRepository;
    private Presenter $presenter;
    private string $pageId;

    private Nette\Database\Table\ActiveRow $activePage;

    /**
     * ArticleEditForm constructor.
     * @param Presenter $presenter
     * @param PageRepository $pageRepository
     * @param string $pageId
     */
    public function __construct(Presenter $presenter, PageRepository $pageRepository, string $pageId)
    {
        $this->presenter = $presenter;
        $this->pageRepository = $pageRepository;
        $this->pageId = $pageId;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $page = $this->pageRepository->findPageById($this->pageId);

        $form->addText('name', 'Název stránky')
            ->setRequired()
            ->setMaxLength(50)
            ->setDefaultValue($page->name);
        $form->addText('url', 'URL stránky')
            ->setRequired()
            ->setMaxLength(128)
            ->setDefaultValue($page->url);
        $form->addText('keywords', 'Klíčová slova')
            ->setMaxLength(100)
            ->setDefaultValue($page->keywords);
        $form->addText('description', 'Popisek')
            ->setMaxLength(170)
            ->setDefaultValue($page->description);
        //category input//
        $form->addTextArea('content', 'Obsah')
            ->setDefaultValue($page->content);

        $form->addSubmit('submit')
            ->setRequired()
            ->setHtmlAttribute('onSubmit', 'tinyMCE.triggerSave()');


        $this->activePage = $page;
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws Nette\Application\AbortException
     */
    public function success(Form $form, stdClass $values): void {
        $duplicate = false;
        if($this->pageRepository->isDuplicated($values->url) && trim($values->url) !== trim($this->activePage->url)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->pageRepository->updatePage($this->pageId, [
                'name' => $values->name,
                'url' => Utils::parseURL($values->url),
                'description' => $values->description,
                'keywords' => $values->keywords,
                'content' => $values->content,
            ]);
            $this->presenter->flashMessage('Změna byla aplikována', 'success');
            $this->presenter->redirect('Page:edit', Utils::parseURL($values->url));
        } else {
            $form->addError('Stránka se stejným URL názvem již existuje, prosím zvolte jinou URL.');
        }
    }
}
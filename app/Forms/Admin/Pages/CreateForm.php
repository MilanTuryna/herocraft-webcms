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
class CreateForm
{
    use Nette\SmartObject;

    private App\Model\PageRepository $pageRepository;
    private Presenter $presenter;

    /**
     * ArticleEditForm constructor.
     * @param Presenter $presenter
     * @param PageRepository $pageRepository
     */
    public function __construct(Presenter $presenter, PageRepository $pageRepository)
    {
        $this->presenter = $presenter;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $form->addText('name', 'Název stránky')
            ->setRequired()
            ->setMaxLength(50);
        $form->addText('url', 'URL stránky')
            ->setRequired()
            ->setMaxLength(128);
        $form->addText('keywords', 'Klíčová slova')->setMaxLength(100);
        $form->addText('description', 'Popisek')->setMaxLength(170);
        $form->addTextArea('content', 'Obsah');
        $form->addSubmit('submit')
            ->setRequired()
            ->setHtmlAttribute('onSubmit', 'tinyMCE.triggerSave()');


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
        if($this->pageRepository->isDuplicated($values->url)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->pageRepository->createPage([
                'name' => $values->name,
                'url' => Utils::parseURL($values->url),
                'description' => $values->description,
                'keywords' => $values->keywords,
                'content' => $values->content,
            ]);
            $this->presenter->flashMessage('Stránka byla úspěšně přidána. Pokud se stránka v seznamu neobjevila, refreshnete stránku!', 'success');
            $this->presenter->redirect('Page:list');
        } else {
            $form->addError('Stránka se stejným URL názvem již existuje, prosím zvolte jinou URL.');
        }
    }
}
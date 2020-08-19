<?php

namespace App\Forms\Admin\Article;

use App;
use App\Model\ArticleRepository;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

/**
 * Class EditForm
 * @package App\Forms\Admin\Article
 */
class EditForm
{
    use Nette\SmartObject;

    private ArticleRepository $articleRepository;
    private Presenter $presenter;
    private string $articleId;

    private Nette\Database\Table\ActiveRow $article;

    /**
     * ArticleEditForm constructor.
     * @param Presenter $presenter
     * @param ArticleRepository $articleRepository
     * @param string $articleId
     */
    public function __construct(Presenter $presenter, ArticleRepository $articleRepository, string $articleId)
    {
        $this->presenter = $presenter;
        $this->articleRepository = $articleRepository;
        $this->articleId = $articleId;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $article = $this->articleRepository->findArticleById($this->articleId);
        $categories = $this->articleRepository->getCategoryRepository()->findCategories();
        $nowCategory = $this->articleRepository->getCategoryRepository()->findCategoryById($this->articleId) ?: ['name' => 'Nezařazeno', 'id' => false];

        $form->addText('name', 'Název článku')
            ->setRequired()
            ->setMaxLength(50)
            ->setDefaultValue($article->name);
        $form->addText('url', 'URL článku')
            ->setRequired()
            ->setMaxLength(128)
            ->setDefaultValue($article->url);
        $form->addUpload('miniature', 'Miniatura');
        $form->addText('keywords', 'Klíčová slova')
            ->setMaxLength(100)
            ->setDefaultValue($article->keywords);
        $form->addText('description', 'Popisek')
            ->setMaxLength(170)
            ->setDefaultValue($article->description);
        //category input//
        $form->addTextArea('content', 'Obsah')
            ->setDefaultValue($article->content);
        $form->addText('author', 'Autor')
            ->setDefaultValue($article->author);
        $form->addText('created_at', 'Datum vytvoření')
            ->setDefaultValue($article->created_at);

        $categoriesValues = ['Nezařazeno' => 'Nezařazeno'];
        foreach ($categories as $ctg) {
            $categoriesValues[$ctg->id] = $ctg->name;
        }

        $form->addSelect('category', 'Category', $categoriesValues)
            ->setRequired()
            ->setDefaultValue($nowCategory['id'] ?: $nowCategory['name']);

        $form->addSubmit('submit')
            ->setRequired()
            ->setHtmlAttribute('onSubmit', 'tinyMCE.triggerSave()');


        $this->article = $article;
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
        if($this->articleRepository->isDuplicated($values->url) && trim($values->url) !== trim($this->article->url)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->articleRepository->updateArticle($this->articleId, [
                'name' => $values->name,
                'url' => App\Model\Utils::parseURL($values->url),
                'description' => $values->description,
                'keywords' => $values->keywords,
                'content' => $values->content,
                'category_id' => $values->category !== "NEZAŘAZENO" ? $values->category : ''
            ]);
            if($values->miniature->isOk() && $values->miniature->isImage()) {
                if($this->articleRepository->getMiniature($this->articleId)) {
                    $this->articleRepository->deleteMiniature($this->articleId);
                }
                $this->articleRepository->setMiniature($this->articleId, $values->miniature);
            } else {
                $this->presenter->flashMessage('Článek byl úspěšně změněn, ale miniatura zůstala původní.', 'info');
            }
            // author, created_at pouze "na parádu", nic se nebude měnit
            $this->presenter->flashMessage('Změna byla aplikována', 'success');
            $this->presenter->redirect('Article:edit', $values->url);
        } else {
            $form->addError('Článek se stejným URL názvem již existuje, prosím zvolte jiný název..');
        }
    }
}
<?php


namespace App\Forms\Admin\Article;

use App;
use App\Model\ArticleRepository;
use App\Model\Admin\Object\Administrator;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

use stdClass;

/**
 * Class CreateForm
 * @package App\Forms\Admin\Article
 */
class CreateForm
{
    use Nette\SmartObject;

    private ArticleRepository $articleRepository;
    private Presenter $presenter;
    private Administrator $administrator;

    /**
     * CreateForm constructor.
     * @param Presenter $presenter
     * @param ArticleRepository $articleRepository
     * @param Administrator $administrator
     */
    public function __construct(Presenter $presenter, ArticleRepository $articleRepository, Administrator $administrator)
    {
        $this->administrator = $administrator;
        $this->presenter = $presenter;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $categories = $this->articleRepository
            ->getCategoryRepository()
            ->findCategories();

        $form = new Form;

        $form->addText('name', 'Název článku')
            ->setRequired()
            ->setMaxLength(50);
        $form->addText('url', 'URL článku')
            ->setRequired()
            ->setMaxLength(128);
        $form->addSelect('secret', 'Skrytá stránka', [
            0 => "Ne (klasické zobrazení v navigaci)",
            1 => "Ano (přístup pouze přes přímé URL)",
        ])->setDefaultValue(0)->setRequired();
        $form->addUpload('miniature', 'Miniatura');
        $form->addText('keywords', 'Klíčová slova')->setMaxLength(100);
        $form->addText('description', 'Popisek')->setMaxLength(170);
        $form->addTextArea('content', 'Obsah');
        $form->addText('author', 'Autor')->setDefaultValue($this->administrator->getName());
        $form->addText('created_at', 'Datum vytvoření');

        $categoriesValues = ['Nezařazeno' => 'Nezařazeno'];
        foreach ($categories as $ctg) {
            $categoriesValues[$ctg->id] = $ctg->name;
        }

        $form->addSelect('category', 'Category', $categoriesValues)
            ->setDefaultValue('Nezařazeno')
            ->setRequired();

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
    public function success(Form $form, stdClass $values) {
        if(!$this->articleRepository->isDuplicated($values->url)) {
            $row = $this->articleRepository->createArticle([
                'name' => $values->name,
                'url' => App\Model\Utils::parseURL($values->url),
                'description' => $values->description,
                'keywords' => $values->keywords,
                'content' => $values->content,
                'created_at' => date('Y-m-d H:i:s'),
                'secret' => $values->secret,
                'author' => $this->administrator->getName(),
                'category_id' => $values->category !== "NEZAŘAZENO" ? $values->category : ''
            ]);
            if($values->miniature->isOk() && $values->miniature->isImage()) {
                $this->articleRepository->setMiniature($row->id, $values->miniature);
            } else {
                $this->presenter->flashMessage('Článek byl úspěšně přidán, ale miniatura se nenahrála, něco se pokazilo!', 'danger');
            }
            $this->presenter->flashMessage('Článek ' . $values->name . ' byl úspěšně přidán! Pokud se článek nezobrazil, refreshněte stránku.', 'success');
            $this->presenter->redirect('Article:list');
        } else {
            $form->addError('Článek se stejným URL názvem již existuje, prosím vytvořte jiný.');
        }
    }
}
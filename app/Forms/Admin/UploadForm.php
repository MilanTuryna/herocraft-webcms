<?php


namespace App\Forms\Admin;


use App\Model\Admin\UploadManager;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Http\FileUpload;
use stdClass;

/**
 * Class UploadForm
 * @package App\Forms\Admin
 */
class UploadForm
{
    private Presenter $presenter;

    /**
     * UploadForm constructor.
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addMultiUpload("upload")->setRequired()
            ->addRule($form::IMAGE, "Zadaný soubor musí být obrázek!'");
        $form->addSubmit("submit")->setRequired();

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values) {
        UploadManager::add(function ($errorFiles) {
            if(!empty($errorFiles)) $this->presenter->flashMessage("Vypadá to, že některý ze zadaných souborů, nebyl validní. Zkontrolujte.", "danger");
        }, $values->upload);
        $this->presenter->flashMessage("Server požadavek úspěšně přijal a zpracovává", "success");
        $this->presenter->redirect("Main:upload");
    }
}
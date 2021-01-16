<?php


namespace App\Forms\Admin\Content\Styles\Button;


use App\Forms\Content\Styles\Button\Data\ButtonStyleFormData;
use App\Front\Styles\ButtonStyles;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;

class CreateButtonStyleForm
{
    use SmartObject;

    private Presenter $presenter;
    private ButtonStyles $buttonStyles;
    public string $successRedirect;

    /**
     * CreateButtonStyleForm constructor.
     * @param Presenter $presenter
     * @param ButtonStyles $buttonStyles
     * @param string $successRedirect
     */
    public function __construct(Presenter $presenter, ButtonStyles $buttonStyles, string $successRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->buttonStyles = $buttonStyles;
        $this->successRedirect = $successRedirect;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Název tlačítka')->setRequired(true);
        $form->addText('class', 'Třída tlačítka')->setRequired(true);
        $form->addTextArea('css', 'Kaskádové styly vázané k tlačítku')->setRequired(false);
        $form->addSubmit('submit', 'Vytvořit nové tlačítko');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param ButtonStyleFormData $data
     * @throws AbortException
     */
    public function success(Form $form, ButtonStyleFormData $data): void {
        if($this->buttonStyles->createStyle($this->buttonStyles::getIterableRow($data->name, $data->class, $data->css ?: ''))) {
            $this->presenter->flashMessage("Styl " . $data->name . " byl úspěšně vytvořen!", "success");
            $this->presenter->redirect($this->successRedirect);
        } else {
            $form->addError('Při vytváření nového stylu došlo k neznámě chybě!');
            $this->presenter->redirect('this');
        }
    }
}
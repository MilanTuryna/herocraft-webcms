<?php


namespace App\Forms\Minecraft;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use stdClass;

class FilterForm
{
    private Presenter $presenter;
    private string $successRedirect;

    /**
     * ChatFilterForm constructor.
     * @param Presenter $presenter
     * @param string $successRedirect
     */
    public function __construct(Presenter $presenter, string $successRedirect)
    {
        $this->presenter = $presenter;
        $this->successRedirect = $successRedirect;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $form->addText('timeStart', 'Od')->setHtmlType("date")->setRequired();
        $form->addText('timeEnd', 'Do')->setHtmlType("date")->setRequired();
        $form->addText('subjects')->setRequired();
        $form->addSubmit('submit')->setRequired();

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
        if($values->timeStart < $values->timeEnd) {
            $subjects = explode(" ", $values->subject);
            if($subjects) {
                $this->presenter->redirect($this->successRedirect, [
                    $values->timeStart, $values->timeEnd, $subjects
                ]);
            } else {
                $form->addError("Žádného hráče jste nezadal!");
            }
        } else {
            $form->addError("První datum (od) musí být vždy časově dřív, jak druhý datum (do)");
        }
    }
}
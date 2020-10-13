<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\ChatLog;

use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;

use stdClass;

/**
 * Class ChatFilterForm
 * @package App\Forms\Minecraft
 */
class ChatFilterForm
{
    private ChatLog $chatLog;
    private Presenter $presenter;

    /**
     * ChatFilterForm constructor.
     * @param $chatLog
     * @param $presenter
     */
    public function __construct(ChatLog $chatLog, Presenter $presenter)
    {
        $this->chatLog = $chatLog;
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $form->addText('timeStart', 'Od')->setHtmlType("date")->setRequired();
        $form->addText('timeEnd', 'Do')->setHtmlType("date")->setRequired();
        $form->addText("players")->setRequired();
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
            $players = explode(" ", $values->players);
            if($players) {
                $this->presenter->redirect("Minecraft:filterChat", [
                    $values->timeStart, $values->timeEnd, $players
                ]);
            } else {
                $form->addError("Žádného hráče jste nezadal!");
            }
        } else {
            $form->addError("První datum (od) musí být vždy časově dřív, jak druhý datum (do)");
        }
    }
}
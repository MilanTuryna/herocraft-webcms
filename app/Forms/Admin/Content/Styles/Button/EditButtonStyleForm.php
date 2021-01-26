<?php

namespace App\Forms\Admin\Content\Styles\Button;

use App\Forms\Content\Styles\Button\Data\ButtonStyleFormData;
use App\Front\Parsers\Exceptions\SyntaxError;
use App\Front\Styles\ButtonStyles;
use App\Model\Front\Parsers\CSSParser;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;

/**
 * Class EditButtonStyleForm
 * @package App\Forms\Admin\Content\Styles\Button
 */
class EditButtonStyleForm
{
    use SmartObject;

    private Presenter $presenter;
    private ButtonStyles $buttonStyles;
    private int $buttonId;
    public string $afterRedirect;

    /**
     * EditButtonStyleForm constructor.
     * @param Presenter $presenter
     * @param ButtonStyles $buttonStyles
     * @param int $buttonId
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, ButtonStyles $buttonStyles, int $buttonId, string $afterRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->buttonStyles = $buttonStyles;
        $this->buttonId = $buttonId;
        $this->afterRedirect = $afterRedirect;
    }

    public function create(): Form {
        $form = new Form();
        $buttonStyle = $this->buttonStyles->getStyleById($this->buttonId);
        $form->addText('name', 'Název tlačítka')->setRequired(true)->setDefaultValue($buttonStyle->name);
        $form->addText('class', 'Třída tlačítka')->setRequired(true)->setDefaultValue($buttonStyle->class);
        $form->addTextArea('css', 'Kaskádové styly vázané k tlačítku')->setRequired(true)->setDefaultValue($buttonStyle->css);
        $form->addSubmit('submit', 'Aktualizovat změny');
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
    public function success(Form $form, ButtonStyleFormData $data) {
        try {
            $queryData = $this->buttonStyles::getIterableRow($data->name, $data->class, $data->css);
            $cssParser = new CSSParser($data->css, $data->class, true);
            $disabledSelectors = $cssParser->removeDisabledSelectors();
            $computedCode = $cssParser->getComputedCode(true);
            if($this->buttonStyles->editStyle($this->buttonId, $queryData)) {
                $this->presenter->flashMessage("Provedené změny byla úspěšně aktualizovány!", "success");
                if(!$computedCode && $disabledSelectors) $this->presenter->flashMessage("V zadaném CSS kódu byly odstraněny nežádoucí styly a kód je momentálně prázdný.");
            } else {
                $form->addError('Při aktualizaci změn došlo k neznámé chybě.');
            }
            $this->presenter->redirect($this->afterRedirect);
        } catch (SyntaxError $syntaxError) {
            $form->addError('V syntaxi zadaného CSS kódu byla nalezena chyba, zkontrolujte si ho prosím.');
        }
    }
}
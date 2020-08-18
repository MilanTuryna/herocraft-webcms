<?php

namespace App\Forms\Panel\Main;

use App\Model\API\Plugin\FastLogin;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class FastLoginForm
 * @package App\Forms\Panel\Main
 */
class FastLoginForm
{
    private FastLogin $fastLogin;
    private Presenter $presenter;
    private string $username;

    /**
     * FastLoginForm constructor.
     * @param Presenter $presenter
     * @param FastLogin $fastLogin
     * @param string $username
     */
    public function __construct(Presenter $presenter, FastLogin $fastLogin, string $username)
    {
        $this->presenter = $presenter;
        $this->fastLogin = $fastLogin;
        $this->username = $username;
    }

    public function create(): Form {
        $form = new Form();
        $form->addButton('original');
        $form->addButton('warez');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    public function success(Form $form, \stdClass $values) {
        if($values->original) {
            $this->fastLogin->setAutoLogin($this->username, true);
            $this->presenter->flashMessage('Automatické přihlašování bylo úspěšně zapnuto', 'success');
        } else if($values->warez) {
            $this->fastLogin->setAutoLogin($this->username, false);
            $this->presenter->flashMessage('Automatické přihlašování bylo úspěšně vypnuto', 'success');
        } else {
            $form->addError('Při nastavení automatického přihlašování nastala chyba.');
        }
    }
}
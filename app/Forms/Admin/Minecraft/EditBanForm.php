<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;
use stdClass;
use Tracy\Debugger;

/**
 * Class EditBanForm
 * @package App\Forms\Minecraft
 */
class EditBanForm
{
    private Presenter $presenter;
    private Bans $bans;
    private string $bannedPlayer;

    /**
     * EditBanForm constructor.
     * @param Presenter $presenter
     * @param Bans $bans
     * @param string $bannedPlayer
     */
    public function __construct(Presenter $presenter, Bans $bans, string $bannedPlayer)
    {
        $this->presenter = $presenter;
        $this->bans = $bans;
        $this->bannedPlayer = $bannedPlayer;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $ban = $this->bans->getBanByNick($this->bannedPlayer)->fetch();

        $form = new Form;
        $form->addText("reason")->setRequired()->setDefaultValue($ban->reason);
        $form->addText('expires')->setDefaultValue(DateTime::from((round($ban->expires/1000)))->format("j.n.Y H:i"));
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
     */
    public function success(Form $form, stdClass $values): void {
        // TODO: Test if all  working
        $this->bans->updateBanByNick([
            "reason" => $values->reason,
            "expires" => DateTime::from($values->expired)->getTimestamp()*1000,
        ], $this->bannedPlayer);
        $this->presenter->flashMessage("Záznam hráče " . $this->bannedPlayer . " byl úspěšně změněn, podle zadaných hodnot.", "success");
    }
}
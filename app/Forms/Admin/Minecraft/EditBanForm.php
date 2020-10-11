<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Nette\Application\AbortException;
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
     * @throws AbortException
     */
    public function success(Form $form, stdClass $values): void {
        try {
            $expires = DateTime::from($values->expires)->getTimestamp()*1000;
            $this->bans->updateBanByNick([
                "reason" => $values->reason,
                "expires" => $expires], $this->bannedPlayer);
            $this->presenter->flashMessage("Záznam hráče " . $this->bannedPlayer . " byl úspěšně změněn, podle zadaných hodnot.", "success");
            $this->presenter->redirect("Minecraft:editBan", $this->bannedPlayer);
        } catch (\Exception $e) {
            $this->presenter->flashMessage("Zkontrolujte si prosím, jestli jste zadal čas ve validním časovém formátu", "danger");
            $this->presenter->redirect("Minecraft:editBan", $this->bannedPlayer);
        }
    }
}
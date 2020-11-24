<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;
use stdClass;

/**
 * Class EditIpBanForm
 * @package App\Forms\Minecraft
 */
class EditIpBanForm
{
    Private Bans $bans;
    Private Presenter $presenter;
    Private string $ip;

    /**
     * EditIpBanForm constructor.
     * @param Bans $bans
     * @param Presenter $presenter
     * @param string $ip
     */
    public function __construct(Bans $bans, Presenter $presenter, string $ip)
    {
        $this->bans = $bans;
        $this->presenter = $presenter;
        $this->ip = $ip;
    }

    /**
     * @return Form
     * @noinspection DuplicatedCode
     * @throws Exception
     */
    public function create(): Form {
        $ipBan = $this->bans->getIPBanByIP($this->ip)->fetch();

        $form = new Form;
        $form->addText("reason")->setRequired()->setDefaultValue($ipBan->reason);
        $form->addText('expires')->setDefaultValue(DateTime::from((round($ipBan->expires/1000)))->format("j.n.Y H:i"));
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
        try {
            $expires = DateTime::from($values->expires)->getTimestamp()*1000;
            $this->bans->updateIpBanByIp([
                "reason" => $values->reason,
                "expires" => $expires], $this->ip);
            $this->presenter->flashMessage("Záznam IP adresy " . $this->ip . " byl úspěšně změněn, podle zadaných hodnot.", "success");
            $this->presenter->redirect("Minecraft:editBan", $this->ip);
        } catch (Exception $e) {
            $this->presenter->flashMessage("Zkontrolujte si prosím, jestli jste zadal čas ve validním časovém formátu", "danger");
            $this->presenter->redirect("Minecraft:editBan", $this->ip);
        }
    }
}
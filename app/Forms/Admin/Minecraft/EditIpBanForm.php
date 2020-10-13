<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

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

    public function create(): Form {
        $form = new Form;
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     */
    public function success(Form $form, \stdClass $values) {

    }
}
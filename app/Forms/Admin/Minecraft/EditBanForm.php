<?php


namespace App\Forms\Minecraft;


use App\Model\API\Plugin\Bans;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

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

    public function create(): Form {
        $form = new Form;
        return $form;
    }

    public function success(Form $form, \stdClass $values): void {

    }
}
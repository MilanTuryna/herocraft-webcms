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

    /**
     * EditBanForm constructor.
     * @param Presenter $presenter
     * @param Bans $bans
     */
    public function __construct(Presenter $presenter, Bans $bans)
    {
        $this->presenter = $presenter;
        $this->bans = $bans;
    }

    public function create(): Form {
        $form = new Form;
        return $form;
    }

    public function success(Form $form, \stdClass $values): void {
        
    }
}
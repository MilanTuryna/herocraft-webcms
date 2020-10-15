<?php


namespace App\Presenters\AdminModule;


use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;

/**
 * Class MinecraftSeniorPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftSeniorPresenter extends AdminBasePresenter
{
    /**
     * MinecraftSeniorPresenter constructor.
     * @param Authenticator $authenticator
     */
    public function __construct(Authenticator $authenticator)
    {
        parent::__construct($authenticator);
    }
}
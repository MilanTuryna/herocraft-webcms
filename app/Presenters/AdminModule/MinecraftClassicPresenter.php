<?php


namespace App\Presenters\AdminModule;


use App\Model\API\Plugin\Classic\Economy;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;

class MinecraftClassicPresenter extends AdminBasePresenter
{
    private Economy $classicEconomy;

    public function __construct(Authenticator $authenticator, Economy $classicEconomy)
    {
        parent::__construct($authenticator);

        $this->classicEconomy = $classicEconomy;
    }
}
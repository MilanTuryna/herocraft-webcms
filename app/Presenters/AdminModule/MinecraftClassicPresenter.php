<?php


namespace App\Presenters\AdminModule;


use App\Model\API\Plugin\Classic\Economy;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;

/**
 * Class MinecraftClassicPresenter
 * @package App\Presenters\AdminModule
 */
class MinecraftClassicPresenter extends AdminBasePresenter
{
    private Economy $classicEconomy;

    /**
     * MinecraftClassicPresenter constructor.
     * @param Authenticator $authenticator
     * @param Economy $classicEconomy
     */
    public function __construct(Authenticator $authenticator, Economy $classicEconomy)
    {
        parent::__construct($authenticator);

        $this->classicEconomy = $classicEconomy;
    }
}
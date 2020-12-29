<?php


namespace App\Model\DI;

/**
 * Class Cron
 * @package App\Model\DI
 */
class Cron
{
    private string $authenticationPassword;

    /**
     * Cron constructor.
     * @param $authenticationPassword
     */
    public function __construct($authenticationPassword)
    {
        $this->authenticationPassword = $authenticationPassword;
    }

    /**
     * @return string
     */
    public function getAuthenticationPassword(): string
    {
        return $this->authenticationPassword;
    }
}
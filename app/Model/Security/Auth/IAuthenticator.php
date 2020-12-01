<?php

namespace App\Model\Security\Auth;

use Nette\Database\Table\ActiveRow;

/**
 * Interface IAuthenticator
 * @package App\Model\Security
 */
interface IAuthenticator {
    const EXPIRATION = '14 days';

    /**
     * @param array $credentials
     * @param string $expiration
     */
    public function login(array $credentials, string $expiration = self::EXPIRATION): void;
    public function logout(): void;
    public function getUser(): ?ActiveRow;
}
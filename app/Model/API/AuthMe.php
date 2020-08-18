<?php


namespace App\Model\API;

use Nette;

/**
 * Class AuthMe
 * @package App\Model
 */
class AuthMe
{
    use Nette\SmartObject;

    /**
     * @param $pass
     * @return string
     */
    public static function hash($pass) {
        return hash('sha256',  $pass);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function isValidLogin(string $password, string $hash): bool {
        $parts = explode('$', $hash);
        $salt = $parts[2];
        $hashed = hash('sha256',hash('sha256', $password).$salt);
        $hashed = '$SHA$'.$salt.'$'.$hashed;
        return ($hashed == $hash) ? true : false;
    }
}
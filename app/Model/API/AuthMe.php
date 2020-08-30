<?php


namespace App\Model\API;

/**
 * Class AuthMe
 * @package App\Model\API
 * @see https://codeclimate.com/github/AuthMe/AuthMeReloaded/samples/website_integration/Sha256.php/source
 */
class AuthMe
{
    private $CHARS;

    const SALT_LENGTH = 16;

    public function __construct() {
        $this->CHARS = self::initCharRange();
    }

    public function isValidPassword($password, $hash) {
        // $SHA$salt$hash, where hash := sha256(sha256(password) . salt)
        $parts = explode('$', $hash);
        return count($parts) === 4 && $parts[3] === hash('sha256', hash('sha256', $password) . $parts[2]);
    }

    public function hash($password) {
        $salt = $this->generateSalt();
        return '$SHA$' . $salt . '$' . hash('sha256', hash('sha256', $password) . $salt);
    }

    /**
     * @return string randomly generated salt
     */
    private function generateSalt() {
        $maxCharIndex = count($this->CHARS) - 1;
        $salt = '';
        for ($i = 0; $i < self::SALT_LENGTH; ++$i) {
            $salt .= $this->CHARS[mt_rand(0, $maxCharIndex)];
        }
        return $salt;
    }

    private static function initCharRange() {
        return array_merge(range('0', '9'), range('a', 'f'));
    }
}
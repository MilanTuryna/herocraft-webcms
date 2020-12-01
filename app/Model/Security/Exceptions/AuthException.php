<?php


namespace App\Model\Security\Exceptions;

/**
 * Class AuthException
 * @package App\Model\Security\Exceptions
 *
 * Exception class for catching throws about auth errors (bad nick or password, no permissions to authorize..)
 */
final class AuthException extends \Exception {};
<?php

namespace App\WebLoader\Exceptions;

use Exception;

/**
 * Class ParseError
 * @package App\WebLoader\Exceptions
 */
class ParseError extends \Exception
{
    private ?Exception $originalException;

    /**
     * ParseError constructor.
     * @param string $message
     * @param Exception|null $originalException
     */
    public function __construct($message = "", Exception $originalException = null)
    {
        parent::__construct($message, 0);

        $this->originalException = $originalException;
    }

    /**
     * @return Exception
     */
    public function getOriginalException(): ?Exception
    {
        return $this->originalException;
    }
}
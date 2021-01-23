<?php

namespace App\Front\Parsers\Exceptions;

/**
 * Class SyntaxError
 * @package App\Front\Parsers\Exceptions
 */
class SyntaxError extends \Exception
{
    private int $problemLine;

    /**
     * SyntaxError constructor.
     * @param string $message
     * @param int $problemLine
     */
    public function __construct($message = "", int $problemLine = 0)
    {
        $this->problemLine = $problemLine;

        parent::__construct($message);
    }

    /**
     * @return int
     */
    public function getProblemLine(): int
    {
        return $this->problemLine;
    }
}
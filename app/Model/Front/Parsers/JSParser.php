<?php


namespace App\Model\Front\Parsers;

use MatthiasMullie\Minify;

/**
 * Class JSParser
 * @package App\Model\Front\Parsers
 */
class JSParser
{
    private string $rawCode;
    private Minify\JS $minifyJS;

    /**
     * JSParser constructor.
     * @param string $rawCode
     */
    public function __construct(string $rawCode)
    {
        $this->rawCode = $rawCode;
        $this->minifyJS = new Minify\JS($rawCode);
    }

    /**
     * @return string
     */
    public function getRawCode(): string
    {
        return $this->rawCode;
    }

    /**
     * @return string
     */
    public function getCompiledCode(): string {
        return $this->minifyJS->minify();
    }
}
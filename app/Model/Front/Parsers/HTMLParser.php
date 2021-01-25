<?php


namespace App\Model\Front\Parsers;

/**
 * Class HTMLParser
 * @package App\Model\Front\Parsers
 */
class HTMLParser
{
    /**
     * @param string $input
     * @return string
     */
    public static function replaceAsterisk(string $input): string {
        return preg_replace("/\*{1,2}(.*?)\*{1,2}/", "<strong>$1</strong>", $input);
    }
}
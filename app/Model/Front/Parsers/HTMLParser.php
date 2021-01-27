<?php


namespace App\Model\Front\Parsers;

/**
 * Class HTMLParser
 * @package App\Model\Front\Parsers
 */
class HTMLParser
{
    const asteriskPattern = "/\*{1,2}(.*?)\*{1,2}/";
    const doubleDashPattern = "/--(.*?)--/g";

    /**
     * @param string $input
     * @return string
     */
    public static function replaceAsterisk(string $input): string {
        return preg_replace(self::asteriskPattern, "<strong>$1</strong>", $input);
    }

    /**
     * @param string $input
     * @return string
     */
    public static function replaceDoubleDash(string $input) {
        return preg_replace(self::doubleDashPattern, "", $input);
    }
}
<?php


namespace App;

/**
 * Class Constants
 * @package App
 */
class Constants
{
    /**
     * Constant for valid URL, use strtr(string, App\Constants::PARSE_URL)
     */
    const VALID_URL = [
        " " => "-",
        "á" => "a",
        "é" => "e",
        "í" => "i",
        "ó" => "o",
        "ú" => "u",
        "ý" => "y",
        "č" => "c",
        "ď" => "d",
        "ě" => "e",
        "ň" => "n",
        "ř" => "r",
        "š" => "s",
        "ť" => "t",
        "ž" => "z",
        "ů" => "u"
    ];
}
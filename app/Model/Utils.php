<?php


namespace App\Model;

use App;
use DOMDocument;

/**
 *
 * Class Utils
 * @package App\Model
 */
class Utils
{
    /**
     * Function for parsing URL, ex: Jak se připojit => jak-se-pripojit - remove diacritic, accents atd.
     *
     * @param $url
     * @return string
     */
    public static function parseURL($url) {
        return strtr(strtolower($url), App\Constants::VALID_URL);
    }

    /**
     * Function for word declension - sklonovani(3, ["hráč", "hráči", "hráčů"]): hráči
     *
     * @param $pocet
     * @param $slova
     * @return mixed
     */
    public static function sklonovani($pocet, $slova) {
        $pocet = abs($pocet);
        if ($pocet == 1) return $slova[0];
        if ($pocet < 5 && $pocet > 0) return $slova[1];
        return $slova[2];
    }

    /**
     * Getting string date formatted in default MySQL format.
     *
     * @return false|string
     */
    public static function sqlNow() {
        return date('Y-m-d H:i:s');
    }

    public static function substrWithoutHTML($value, $limit) {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        // Strip text with HTML tags, sum html len tags too.
        // Is there another way to do it?
        do {
            $len          = mb_strwidth($value, 'UTF-8');
            $len_stripped = mb_strwidth(strip_tags($value), 'UTF-8');
            $len_tags     = $len - $len_stripped;

            $value = mb_strimwidth($value, 0, $limit + $len_tags, '', 'UTF-8');
        } while ($len_stripped > $limit);

        // Load as HTML ignoring errors
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$value, LIBXML_HTML_NODEFDTD);

        // Fix the html errors
        $value = $dom->saveHtml($dom->getElementsByTagName('body')->item(0));
        $value = mb_strimwidth($value, 6, mb_strwidth($value, 'UTF-8') - 13, '', 'UTF-8'); // <body> and </body>
        return preg_replace('/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/', '', $value);
    }
}
<?php


namespace App\Model;

use App;

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
}
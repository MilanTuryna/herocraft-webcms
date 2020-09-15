<?php


namespace App\Model\API;


use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * Class CzechCraft
 * @package App\Model\API
 */
class CzechCraft
{
    const SERVER_SLUG = 'herocraft'; // TODO: Dynamic configuration in neon
    const API = 'https://czech-craft.eu/api/server/';

    /**
     * @param $request
     * @return bool|mixed
     */
    private static function getJSON($request = '') {
        try {
            return Json::decode(@file_get_contents(CzechCraft::API . CzechCraft::SERVER_SLUG . "/" . $request));
        } catch (JsonException $e) {
            return false;
        }
    }

    public static function getServer() {
        return self::getJSON();
    }

    /**
     * @param string $nick
     * @return bool|mixed
     */
    public static function getPlayerInformation(string $nick) {
        return self::getJSON('player/' . $nick);
    }

    /**
     * @return bool|mixed
     */
    public static function getTopPlayers() {
        return self::getJSON('voters');
    }
}
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
    const SERVER_SLUG = 'pvpcraft';
    const API = 'https://czech-craft.eu/api/server/';

    /**
     * @param $request
     * @return bool|mixed
     */
    private function getJSON($request = '') {
        try {
            return Json::decode(@file_get_contents(CzechCraft::API . CzechCraft::SERVER_SLUG . "/" . $request));
        } catch (JsonException $e) {
            return false;
        }
    }

    /**
     * @param string $nick
     * @return bool|mixed
     */
    public function getPlayerInformation(string $nick) {
        return $this->getJSON('player/' . $nick);
    }

    /**
     * @return bool|mixed
     */
    public function getTopPlayers() {
        return $this->getJSON('voters');
    }
}
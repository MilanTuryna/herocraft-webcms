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
    const API = 'https://czech-craft.eu/api/server/';

    /**
     * @var string $slug
     */
    private string $slug;

    /**
     * CzechCraft constructor.
     * @param string $slug
     * czechcraft.server_slug (used to generate API path) from configuration - Dependency Injection
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    private function getJSON($request = '') {
        try {
            return Json::decode(@file_get_contents(CzechCraft::API . $this->slug . "/" . $request));
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

    /**
     * @return bool|mixed
     */
    public function getServer() {
        return $this->getJSON();
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}
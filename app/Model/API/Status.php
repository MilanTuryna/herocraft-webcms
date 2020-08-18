<?php

namespace App\Model\API;

use Nette;
use Nette\Utils\Json;
use Nette\Caching\Cache;
use Nette\Utils\JsonException;

class Status
{
    use Nette\SmartObject;

    private string $api = "https://api.mcsrvstat.us/2/";
    private string $ip;
    private Cache $cache;

    /**
     * Status constructor.
     * @param string $ip
     * @param Cache $cache
     */
    public function __construct(string $ip, Cache $cache)
    {
        $this->ip = $ip;
        $this->cache = $cache;
    }

    /**
     * @return bool|mixed
     */
    private function getResponse()
    {
        try {
            return Json::decode(@file_get_contents($this->api . $this->ip));
        } catch (JsonException $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getCachedJson() {
        if(is_null($this->cache->load('status_cache'))) {
            $this->cache->save('status_cache', $this->getResponse(), [
                Cache::EXPIRE => "10 minutes"
            ]);
        }

        return $this->cache->load('status_cache');
    }
}
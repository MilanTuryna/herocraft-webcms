<?php


namespace App\Model\Panel;


use App\Model\API\Minecraft;
use App\Model\API\Plugin\Friends;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;

class MojangRepository
{
    private Cache $cache;
    private Friends $friends;

    public function __construct(IStorage $storage, Friends $friends)
    {
        $this->cache = new Cache($storage);
        $this->friends = $friends;
    }

    public function getProfile($username) {
        if(is_null($this->cache->load('mojang_profile_' . $username))) {
            $this->cache->save('mojang_profile_' . $username, Minecraft::getProfile($username), [
                Cache::EXPIRE => "2 hours"
            ]);
        }

        return $this->cache->load('mojang_profile_' . $username);
    }

    public function getUUID($username) {
        if(is_null($this->cache->load('mojang_uuid_' . $username))) {
            $this->cache->save('mojang_uuid_' . $username, $this->friends->getRowByName($username)->uuid, [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load('mojang_uuid_'. $username);
    }
}
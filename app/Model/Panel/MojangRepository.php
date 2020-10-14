<?php


namespace App\Model\Panel;


use App\Model\API\Minecraft;
use App\Model\API\Plugin\Friends;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;

/**
 * Class MojangRepository
 * @package App\Model\Panel
 */
class MojangRepository
{
    private Cache $cache;
    private Friends $friends;

    /**
     * MojangRepository constructor.
     * @param IStorage $storage
     * @param Friends $friends
     */
    public function __construct(IStorage $storage, Friends $friends)
    {
        $this->cache = new Cache($storage);
        $this->friends = $friends;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getProfile($username) {
        if(is_null($this->cache->load('mojang_profile_' . $username))) {
            $this->cache->save('mojang_profile_' . $username, Minecraft::getProfile($username), [
                Cache::EXPIRE => "2 hours"
            ]);
        }

        return $this->cache->load('mojang_profile_' . $username);
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getMojangUUID($username) {
        if(is_null($this->cache->load('mojang_originaluuid_'. $username))) {
            $this->cache->save('mojang_originaluuid_' . $username,  Minecraft::getUUID($username), [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load('mojang_originaluuid_' . $username);
    }

    /**
     * @param $username
     * @return mixed
     *
     * TODO: null because Friends is deleted
     */
    public function getUUID($username) {
        if(is_null($this->cache->load('mojang_uuid_' . $username))) {
            $this->cache->save('mojang_uuid_' . $username,  null, [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load('mojang_uuid_'. $username);
    }
}
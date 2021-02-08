<?php


namespace App\Model\Panel;


use App\Model\API\Minecraft;
use App\Model\API\Plugin\LuckPerms;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Utils\ArrayHash;

/**
 * Class MojangRepository
 * @package App\Model\Panel
 */
class MojangRepository
{
    private Cache $cache;
    private LuckPerms $luckPerms;

    /**
     * MojangRepository constructor.
     * @param Storage $storage
     * @param LuckPerms $luckPerms
     */
    public function __construct(Storage $storage, LuckPerms $luckPerms)
    {
        $this->cache = new Cache($storage);
        $this->luckPerms = $luckPerms;
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
     */
    public function getUUID($username) {
        if(is_null($this->cache->load('mojang_uuid_' . $username))) {
            $luckPermsRow = $this->luckPerms->getUuidByNick($username);
            if($luckPermsRow) {
                $this->cache->save('mojang_uuid_' . $username,  ArrayHash::from($luckPermsRow->toArray())->uuid, [
                    Cache::EXPIRE => "24 hours"
                ]);
            }
        }

        return $this->cache->load('mojang_uuid_'. $username);
    }
}
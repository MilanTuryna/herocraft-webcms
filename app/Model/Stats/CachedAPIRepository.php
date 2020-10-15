<?php

namespace App\Model\Stats;

use App\Model\API\CzechCraft;
use App\Model\API\Plugin\Bans;
use App\Model\Panel\AuthMeRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\ArrayHash;

/**
 * Class CachedAPIRepository
 */
class CachedAPIRepository
{
    const EXPIRE_TIME = '2 hour';

    private AuthMeRepository $authMeRepository;
    private Cache $cache;
    private Bans $bans;

    /**
     * CachedAPIRepository constructor.
     * @param AuthMeRepository $authMeRepository
     * @param IStorage $storage
     * @param Bans $bans
     */
    public function __construct(AuthMeRepository $authMeRepository, IStorage $storage,
                                Bans $bans)
    {
        $this->authMeRepository = $authMeRepository;
        $this->cache = new Cache($storage);
        $this->bans = $bans;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getUser($name) {
        $cacheName = 'API_user_' . $name;
        if (is_null($this->cache->load($cacheName))) {
            $db = $this->authMeRepository->findByUsername($name);
            $this->cache->save($cacheName,  $db ? ArrayHash::from($db->toArray()) : null, [
                Cache::EXPIRE => self::EXPIRE_TIME
            ]);
        }

        return $this->cache->load($cacheName);
    }

    /**
     * TODO: Complete this method
     * @param $uuid
     * @return mixed
     */
    public function getPermGroups($uuid) {
        $cacheName = 'API_permGroups_' . $uuid;
        if(is_null($this->cache->load($cacheName))) {
            $this->cache->save($cacheName, null, [
                Cache::EXPIRE => self::EXPIRE_TIME
            ]);
        }

        return $this->cache->load($cacheName);
    }

    /**
     * @return mixed
     */
    public function getCzechCraftServer() {
        $cacheName = 'API_czechCraftServer';
        if(is_null($this->cache->load($cacheName))) {
            $this->cache->save($cacheName, CzechCraft::getServer(), [
                Cache::EXPIRE => '1 hour'
            ]);
        }

        return $this->cache->load($cacheName);
    }

    /**
     * @return mixed
     */
    public function getTopVoters() {
        $cacheName = 'API_czechCraftTopVoters';
        if(is_null($this->cache->load($cacheName))) {
            $this->cache->save($cacheName, CzechCraft::getTopPlayers(), [
               Cache::EXPIRE => '24 hour'
            ]);
        }

        return $this->cache->load($cacheName)->data;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function isBanned($name) {
        $cacheName = 'API_ban_' . $name;
        if(is_null($this->cache->load($cacheName))) {
            $this->cache->save($cacheName,  (bool)$this->bans->getBanByNick($name)->fetch(), [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load($cacheName);
    }
}
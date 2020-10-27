<?php

namespace App\Model\Stats;

use App\Model\API\CzechCraft;
use App\Model\API\Plugin\Bans;
use App\Model\API\Plugin\Games\Events;
use App\Model\API\Plugin\Games\HideAndSeek;
use App\Model\API\Plugin\Games\SpleefX;
use App\Model\API\Plugin\LuckPerms;
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
    private HideAndSeek $hideAndSeek;
    private Events $events;
    private LuckPerms $luckPerms;
    private SpleefX $spleefX;

    /**
     * CachedAPIRepository constructor.
     * @param AuthMeRepository $authMeRepository
     * @param IStorage $storage
     * @param Bans $bans
     * @param HideAndSeek $hideAndSeek
     * @param Events $events
     * @param LuckPerms $luckPerms
     * @param SpleefX $spleefX
     */
    public function __construct(AuthMeRepository $authMeRepository, IStorage $storage,
                                Bans $bans, HideAndSeek $hideAndSeek, Events $events, LuckPerms $luckPerms, SpleefX $spleefX)
    {
        $this->authMeRepository = $authMeRepository;
        $this->cache = new Cache($storage);
        $this->bans = $bans;
        $this->hideAndSeek = $hideAndSeek;
        $this->events = $events;
        $this->luckPerms = $luckPerms;
        $this->spleefX = $spleefX;
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
            $this->cache->save($cacheName, $this->luckPerms->getUserGroups($uuid), [
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

    public function getCzechCraftPlayer($player) {
        $cacheName = 'API_czechCraft_' . $player;
        if(is_null($this->cache->load($cacheName))) {
            $this->cache->save($cacheName, CzechCraft::getPlayerInformation($player), [
                Cache::EXPIRE => '24 hour'
            ]);
        }

        return $this->cache->load($cacheName);
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

    /**
     * @param $name
     * @return mixed
     */
    public function getHideAndSeekRow($name) {
        $cacheName = 'API_hideAndSeek_' . $name;
        if(is_null($this->cache->load($cacheName))) {
            $db = $this->hideAndSeek->getRowByName($name)->fetch();
            $this->cache->save($cacheName, $db ? ArrayHash::from($db->toArray()) : null, [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load($cacheName);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getPlayerEventsRecords($name) {
        $cacheName = 'API_events_' . $name;

        if(is_null($this->cache->load($cacheName))) {
            /**
             * Suppressing error: ->fetch() method triggering USER_NOTICE error (because duplicates)
             */
            $db = @$this->events->getPlayerRecordsByName($name);
            $events = [];
            foreach ($db as $d) {
                $events[$d->event_name] = ArrayHash::from(iterator_to_array($d));
            }
            $this->cache->save($cacheName, $events, [
                Cache::EXPIRE => "24 hours"
            ]);
        }


        return $this->cache->load($cacheName);
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function getSpleefStatsByUUID($uuid) {
        $cacheName = 'API_playerSpleefStats_' . $uuid;
        if(is_null($this->cache->load($cacheName))) {
            $db = $this->spleefX->getRowByUuid($uuid)->fetch();
            $this->cache->save($cacheName, $db ? ArrayHash::from($db->toArray()) : null, [
                Cache::EXPIRE => "24 hours"
            ]);
        }

        return $this->cache->load($cacheName);
    }
}
<?php


namespace App\Model\Stats;


use App\Model\API\Plugin\Survival\EpicLevels;
use App\Model\API\Plugin\Survival\Lottery;
use App\Model\API\Plugin\Survival\RoyaleEconomy;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;

/**
 * Class CachedSurvivalRepository
 * @package App\Model\Stats
 */
class CachedSurvivalRepository
{
    const EXPIRE_TIME = '6 hour';

    private Cache $cache;

    private EpicLevels $epicLevels;
    private Lottery $lottery;
    private RoyaleEconomy $royaleEconomy;

    /**
     * CachedSurvivalRepository constructor.
     * @param IStorage $storage
     * @param EpicLevels $epicLevels
     * @param Lottery $lottery
     * @param RoyaleEconomy $royaleEconomy
     */
    public function __construct(IStorage $storage, EpicLevels $epicLevels, Lottery $lottery, RoyaleEconomy $royaleEconomy)
    {
        $this->cache = new Cache($storage);

        $this->epicLevels = $epicLevels;
        $this->lottery = $lottery;
        $this->royaleEconomy = $royaleEconomy;
    }

    public function getEconomy($player) {
        $cache = 'API_SurvivalEconomy_' . $player;
        if(is_null($this->cache->load($cache))) {
            $row = $this->royaleEconomy->getRow($player);
            $result = [
                'playerPurse' => $row->coins
            ];

            $this->cache->save($cache, $result, [
                Cache::EXPIRE => self::EXPIRE_TIME
            ]);
        }

        return $this->cache->load($cache);
    }

    public function getLottery($uuid) {
        $cache = 'API_SurvivalLottery_' . $uuid;
        if(is_null($this->cache->load($cache))) {
            $row = $this->lottery->getRow($uuid);
            $result = [
                'tickets' => $row->tickets,
                'wins' => $row->wins,
                'money' => $row->money
            ];

            $this->cache->save($cache, $result, [
                Cache::EXPIRE => self::EXPIRE_TIME
            ]);
        }

        return $this->cache->load($cache);
    }

    public function getLevels($uuid) {
        $cache = 'API_SurvivalLevels_' . $uuid;
            $row = $this->epicLevels->getRow($uuid);
            $result = [
                'experience' => (double)$row->experience,
                'level' => EpicLevels::getLevel((double)$row->experience),
                'playerKills' => $row->player_kills,
                'mobKills' => $row->mob_kills
            ];

            $this->cache->save($cache, $result, [
                Cache::EXPIRE => self::EXPIRE_TIME
            ]);

        return $this->cache->load($cache);
    }
}
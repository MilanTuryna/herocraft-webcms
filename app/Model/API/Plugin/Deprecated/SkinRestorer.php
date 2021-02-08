<?php


namespace App\Model\API\Plugin\Deprecated;

use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class SkinRestorer
 * @package App\Model\API\Plugin
 */
class SkinRestorer
{
    private Explorer $Explorer;
    private Cache $cache;

    /**
     * SkinRestorer constructor.
     * @param Explorer $Explorer
     *
     * database.skinrestorer
     * @param Storage $storage
     */
    public function __construct(Explorer $Explorer, Storage $storage)
    {
        $this->Explorer = $Explorer;
        $this->cache = new Cache($storage);
    }

    /**
     * @return mixed
     */
    public function getMostUsedSkin() {
        if(is_null($this->cache->load('skinsrestorer_mostUsedSkin'))) {
            $this->cache->save('skinsrestorer_mostUsedSkin', $this->Explorer->table('Players')
                ->select('Skin')
                ->group('Skin')
                ->order('COUNT(*) DESC')
                ->limit(1), [
                Cache::EXPIRE => "24 hour"
            ]);
        }

        return $this->cache->load('skinrestorer_mostUsedSkin');
    }

    /**
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function getSkinByUsername($username) {
        return $this->Explorer->table('Players')->where('Nick = ?', $username)->fetch();
    }
}
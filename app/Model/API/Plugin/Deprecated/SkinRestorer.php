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
    private Explorer $explorer;
    private Cache $cache;

    /**
     * SkinRestorer constructor.
     * @param Explorer $explorer
     *
     * database.skinrestorer
     * @param Storage $storage
     */
    public function __construct(Explorer $explorer, Storage $storage)
    {
        $this->explorer = $explorer;
        $this->cache = new Cache($storage);
    }

    /**
     * @return mixed
     */
    public function getMostUsedSkin() {
        if(is_null($this->cache->load('skinsrestorer_mostUsedSkin'))) {
            $this->cache->save('skinsrestorer_mostUsedSkin', $this->explorer->table('Players')
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
        return $this->explorer->table('Players')->where('Nick = ?', $username)->fetch();
    }
}
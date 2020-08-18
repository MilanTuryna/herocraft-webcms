<?php


namespace App\Model\API\Plugin;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class SkinRestorer
 * @package App\Model\API\Plugin
 */
class SkinRestorer
{
    private Context $context;
    private Cache $cache;

    /**
     * SkinRestorer constructor.
     * @param Context $context
     *
     * database.skinrestorer
     * @param IStorage $storage
     */
    public function __construct(Context $context, IStorage $storage)
    {
        $this->context = $context;
        $this->cache = new Cache($storage);
    }

    /**
     * @return mixed
     */
    public function getMostUsedSkin() {
        if(is_null($this->cache->load('skinsrestorer_mostUsedSkin'))) {
            $this->cache->save('skinsrestorer_mostUsedSkin', $this->context->table('Players')
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
     * @return IRow|ActiveRow|null
     */
    public function getSkinByUsername($username) {
        return $this->context->table('Players')->where('Nick = ?', $username)->fetch();
    }
}
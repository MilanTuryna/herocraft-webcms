<?php

namespace App\Model\API\Plugin\Deprecated;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class EpicLevels
 * @deprecated
 * @package App\Model\API\Plugin\Survival
 */
class EpicLevels
{

    private Explorer $explorer;

    const TABLE = 'epiclevels_players';

    /**
     * EpicLevels constructor.
     * @param Explorer $explorer
     *
     * database.epiclevels
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param $uuid
     * @return Row|ActiveRow|null
     */
    public function getRow($uuid) {
        return $this->explorer->table(self::TABLE)->where('uuid = ?', $uuid)->fetch();
    }

    public static function experience(int $level)
    {
        $a = 0;
        for ($i = 1; $i < $level; $i++) {
            $a += floor($i + 500
                * pow(2, ($i / 10)));
        }
        return $a;
    }

    public static function getLevel($experience): int
    {
        $lastLevel = 0;
        for ($i = 1; $i <= 99; $i++) {
            if (EpicLevels::experience($i) > $experience) break;
            $lastLevel++;
        }

        return $lastLevel;
    }
}
<?php

namespace App\Model\API\Plugin\Survival;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class EpicLevels
 * @package App\Model\API\Plugin\Survival
 */
class EpicLevels
{

    private Context $context;

    const TABLE = 'epiclevels_players';

    /**
     * EpicLevels constructor.
     * @param Context $context
     *
     * database.epiclevels
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $uuid
     * @return IRow|ActiveRow|null
     */
    public function getRow($uuid) {
        return $this->context->table(self::TABLE)->where('uuid = ?', $uuid)->fetch();
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
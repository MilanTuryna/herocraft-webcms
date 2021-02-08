<?php

namespace App\Model\API\Plugin\Survival\Deprecated;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class Lottery
 * @package App\Model\API\Plugin\Survival
 * @deprecated
 */
class Lottery
{
    private Explorer $explorer;

    const TABLE = 'Lottery';

    /**
     * EpicLevels constructor.
     * @param Explorer $explorer
     *
     * database.lottery
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
}
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
    private Explorer $Explorer;

    const TABLE = 'Lottery';

    /**
     * EpicLevels constructor.
     * @param Explorer $Explorer
     *
     * database.lottery
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $uuid
     * @return Row|ActiveRow|null
     */
    public function getRow($uuid) {
        return $this->Explorer->table(self::TABLE)->where('uuid = ?', $uuid)->fetch();
    }
}
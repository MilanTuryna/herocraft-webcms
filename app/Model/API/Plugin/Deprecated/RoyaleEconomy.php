<?php

namespace App\Model\API\Plugin\Survival\Deprecated;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class RoyaleEconomy
 * @package App\Model\API\Plugin\Survival\Deprecated
 * @deprecated
 */
class RoyaleEconomy
{
    private Explorer $Explorer;

    const TABLE = 'PlayerPurse';

    /**
     * EpicLevels constructor.
     * @param Explorer $Explorer
     *
     * database.epiclevels
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function getRow($username) {
        return $this->Explorer->table(self::TABLE)->where('username = ?', $username)->fetch();
    }
}
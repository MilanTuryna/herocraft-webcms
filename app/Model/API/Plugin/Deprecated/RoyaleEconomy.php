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
    private Explorer $explorer;

    const TABLE = 'PlayerPurse';

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
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function getRow($username) {
        return $this->explorer->table(self::TABLE)->where('username = ?', $username)->fetch();
    }
}
<?php

namespace App\Model\API\Plugin\Survival\Deprecated;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class RoyaleEconomy
 * @package App\Model\API\Plugin\Survival\Deprecated
 * @deprecated
 */
class RoyaleEconomy
{
    private Context $context;

    const TABLE = 'PlayerPurse';

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
     * @param $username
     * @return IRow|ActiveRow|null
     */
    public function getRow($username) {
        return $this->context->table(self::TABLE)->where('username = ?', $username)->fetch();
    }
}
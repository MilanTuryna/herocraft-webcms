<?php

namespace App\Model\API\Plugin\Survival;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class Lottery
 * @package App\Model\API\Plugin\Survival
 */
class Lottery
{
    private Context $context;

    const TABLE = 'Lottery';

    /**
     * EpicLevels constructor.
     * @param Context $context
     *
     * database.lottery
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
}
<?php

namespace App\Model\API\Plugin\Senior;

use App\Model\API\Plugin\abstractIconomy;
use Nette\Database\Explorer;

/**
 * Class Economy
 * @package App\Model\API\Plugin\Classic
 *
 * Economy on Games server
 */
class Economy extends abstractIconomy
{
    /**
     * Economy constructor.
     * @param Explorer $Explorer
     *
     * database.seniorEconomy
     */
    public function __construct(Explorer $Explorer)
    {
        parent::__construct($Explorer);
    }
}
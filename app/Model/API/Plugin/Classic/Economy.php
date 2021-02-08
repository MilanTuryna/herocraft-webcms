<?php

namespace App\Model\API\Plugin\Classic;

use App\Model\API\Plugin\abstractIconomy;
use Nette\Database\Explorer;

/**
 * Class Economy
 * @package App\Model\API\Plugin\Classic
 *
 * Economy on classic server
 */
class Economy extends abstractIconomy
{
    /**
     * Economy constructor.
     * @param Explorer $explorer
     *
     * database.classicEconomy
     */
    public function __construct(Explorer $explorer)
    {
        parent::__construct($explorer);
    }
}
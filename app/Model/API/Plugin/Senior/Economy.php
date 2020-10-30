<?php

namespace App\Model\API\Plugin\Games;

use App\Model\API\Plugin\abstractIconomy;
use Nette\Database\Context;

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
     * @param Context $context
     *
     * database.seniorEconomy
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }
}
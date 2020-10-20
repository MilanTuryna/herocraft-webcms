<?php


namespace App\Model\API\Plugin\Games;


use Nette\Database\Context;

/**
 * Class SpleefX
 * @package App\Model\API\Plugin\Games
 */
class SpleefX
{
    const TABLE = "spleefxdata";

    private Context $context;

    /**
     * SpleefX constructor.
     * @param Context $context
     *
     * database.spleefx
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
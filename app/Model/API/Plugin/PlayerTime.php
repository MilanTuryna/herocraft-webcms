<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

/**
 * Class PlayerTime
 * @package App\Model\API\Plugin
 */
class PlayerTime
{
    private Context $context;

    /**
     * PlayerTime constructor.
     * @param Context $context
     * database.playertime
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
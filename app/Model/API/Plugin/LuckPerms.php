<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;

/**
 * Class LuckPerms
 * @package App\Model\API\Plugin
 * @deprecated
 */
class LuckPerms
{
    private Context $context;
    /**
     * LuckPerms constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
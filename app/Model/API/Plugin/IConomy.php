<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;

/**
 * Class IConomy
 * @package App\Model\API\Plugin
 *
 * This class isn't registered in DI container, because constructor Context parameter is dynamic (survival ionomy, skyblock iconomy etc.)
 */
class IConomy
{
    private Context $context;

    /**
     * IConomy constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
}
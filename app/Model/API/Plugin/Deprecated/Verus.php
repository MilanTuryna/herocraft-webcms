<?php


namespace App\Model\API\Plugin\Deprecated;


use Nette\Database\Context;

/**
 * Class Verus
 * @deprecated
 * @package App\Model\API\Plugin
 */
class Verus
{
    private Context $context;

    /**
     * Verus constructor.
     * @param Context $context
     * database.verus
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isBanned($name) {
        if($this->context->table('bans')->where('name = ?', $name)->fetch()) {
            return true;
        }

        return false;
    }
}
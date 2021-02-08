<?php


namespace App\Model\API\Plugin\Deprecated;


use Nette\Database\Explorer;

/**
 * Class Verus
 * @deprecated
 * @package App\Model\API\Plugin
 */
class Verus
{
    private Explorer $explorer;

    /**
     * Verus constructor.
     * @param Explorer $explorer
     * database.verus
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isBanned($name) {
        if($this->explorer->table('bans')->where('name = ?', $name)->fetch()) {
            return true;
        }

        return false;
    }
}
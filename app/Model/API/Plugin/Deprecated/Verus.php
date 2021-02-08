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
    private Explorer $Explorer;

    /**
     * Verus constructor.
     * @param Explorer $Explorer
     * database.verus
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isBanned($name) {
        if($this->Explorer->table('bans')->where('name = ?', $name)->fetch()) {
            return true;
        }

        return false;
    }
}
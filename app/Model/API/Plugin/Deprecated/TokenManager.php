<?php


namespace App\Model\API\Plugin\Deprecated;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;

/**
 * Class TokenManager
 * @deprecated
 * @package App\Model\API
 */
class TokenManager
{
    Private Explorer $Explorer;

    /**
     * TokenManager constructor.
     * @param Explorer $Explorer
     *
     * database.tokenmanager -> config
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $user
     * @return Row|ActiveRow|null
     */
    public function getRow($user) {
        return $this->Explorer->table('tokenmanager')->where('name = ?', $user)->fetch();
    }

    /**
     * @param $user
     * @param $amount
     * @return int
     */
    public function setTokens($user, $amount) {
        return $this->Explorer->table('tokenmanager')->where('name = ?', $user)->update([
            'tokens' => $amount
        ]);
    }

    /**
     * @param $user
     * @param $amount
     * @return int
     */
    public function addTokens($user, $amount) {
        $row = $this->getRow($user);
        if((bool)$row) {
            return $this->Explorer->table('tokenmanager')->where('name = ?', $user)->update([
                'tokens' => ($amount + (int)$row->tokens)
            ]);
        }

        return null;
    }

    /**
     * @param $user
     * @param $amount
     */
    public function minusTokens($user, $amount) {

    }
}
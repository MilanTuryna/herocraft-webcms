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
    Private Explorer $explorer;

    /**
     * TokenManager constructor.
     * @param Explorer $explorer
     *
     * database.tokenmanager -> config
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param $user
     * @return Row|ActiveRow|null
     */
    public function getRow($user) {
        return $this->explorer->table('tokenmanager')->where('name = ?', $user)->fetch();
    }

    /**
     * @param $user
     * @param $amount
     * @return int
     */
    public function setTokens($user, $amount) {
        return $this->explorer->table('tokenmanager')->where('name = ?', $user)->update([
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
            return $this->explorer->table('tokenmanager')->where('name = ?', $user)->update([
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
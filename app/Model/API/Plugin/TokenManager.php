<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;

/**
 * Class TokenManager
 * @package App\Model\API
 */
class TokenManager
{
    Private Context $context;

    /**
     * TokenManager constructor.
     * @param Context $context
     *
     * database.tokenmanager -> config
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $user
     * @return IRow|ActiveRow|null
     */
    public function getRow($user) {
        return $this->context->table('tokenmanager')->where('name = ?', $user)->fetch();
    }

    /**
     * @param $user
     * @param $amount
     * @return int
     */
    public function setTokens($user, $amount) {
        return $this->context->table('tokenmanager')->where('name = ?', $user)->update([
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
            return $this->context->table('tokenmanager')->where('name = ?', $user)->update([
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
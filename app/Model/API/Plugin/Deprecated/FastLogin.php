<?php


namespace App\Model\API\Plugin\Deprecated;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Request;

/**
 * Class FastLogin
 * @deprecated
 * @package App\Model\Panel
 */
class FastLogin
{
    private Explorer $Explorer;
    private Request $request;

    /**
     * FastLogin constructor.
     * @param Explorer $Explorer
     * @param Request $request
     *
     * database.fastlogin -> config
     */
    public function __construct(Explorer $Explorer, Request $request)
    {
        $this->Explorer = $Explorer;
        $this->request = $request;
    }

    /**
     * @param $username
     * @param bool $bool
     */
    public function setAutoLogin($username, bool $bool): void {
        $this->Explorer->query('INSERT INTO premium', [
            'Name' => $username,
            'Premium' => $bool ? 1 : 0,
            'LastIp' => $this->request->getRemoteAddress()
        ], 'ON DUPLICATE KEY UPDATE', [
            'Premium' => $bool ? 1 : 0,
        ]);
    }

    /**
     * @param $username
     * @return Row|ActiveRow|null
     */
    public function getRow($username) {
        return $this->Explorer->table('premium')->where('Name', $username)->fetch();
    }
}
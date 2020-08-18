<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Request;

/**
 * Class FastLogin
 * @package App\Model\Panel
 */
class FastLogin
{
    private Context $context;
    private Request $request;

    /**
     * FastLogin constructor.
     * @param Context $context
     * @param Request $request
     *
     * database.fastlogin -> config
     */
    public function __construct(Context $context, Request $request)
    {
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * @param $username
     * @param bool $bool
     */
    public function setAutoLogin($username, bool $bool): void {
        $this->context->query('INSERT INTO premium', [
            'Name' => $username,
            'Premium' => $bool ? 1 : 0,
            'LastIp' => $this->request->getRemoteAddress()
        ], 'ON DUPLICATE KEY UPDATE', [
            'Premium' => $bool ? 1 : 0,
        ]);
    }

    /**
     * @param $username
     * @return IRow|ActiveRow|null
     */
    public function getRow($username) {
        return $this->context->table('premium')->where('Name', $username)->fetch();
    }
}
<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

/**
 * Class PlayerTime
 * @package App\Model\API\Plugin
 */
class PlayerTime
{
    /**
     * @var Context
     */
    private Context $context;

    /**
     * PlayerTime constructor.
     * @param Context $context
     * database.playertime
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $name
     * @return float|int
     */
    public function getPlayedTime($name) {
        $dbRow = $this->context->table('playertime')->where('lastKnownName = ?', $name)->fetch();
        return $dbRow ? (int)$dbRow->time/10 : null;
    }
}
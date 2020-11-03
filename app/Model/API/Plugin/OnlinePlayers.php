<?php


namespace App\Model\API\Plugin;


use Nette\Database\Context;

class OnlinePlayers
{
    private Context $context;

    const TABLE = "onlineplayers";

    /**
     * OnlinePlayers constructor.
     * @param Context $context
     * database.onlineplayers
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getAllRows() {
        return $this->context->table(self::TABLE);
    }
}
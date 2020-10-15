<?php


namespace App\Model\API\Plugin\Games;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * Class HeavySpleef
 * @package App\Model\API\Plugin
 */
class HeavySpleef
{
    const TABLE = "heavyspleef_statistics";

    private Context $context;

    /**
     * HeavySpleef constructor.
     * @param Context $context
     * database.heavyspleef
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function getRowById($id) {
        return $this->context->table(self::TABLE)->get($id);
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getRowByName($name) {
        return $this->context->table(self::TABLE)->where("last_name = ?", $name);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows(string $order = "rating DESC") {
        return $this->context->table(self::TABLE)->order($order);
    }

    /**
     * @param iterable $data
     * @param $id
     * @return int
     */
    public function updateRowById(iterable $data, $id) {
        return $this->context->table(self::TABLE)->wherePrimary($id)->update($data);
    }
}
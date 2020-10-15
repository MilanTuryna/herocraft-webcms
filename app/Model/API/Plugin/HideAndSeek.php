<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * Class HideAndSeek
 * @package App\Model\API\Plugin
 */
class HideAndSeek
{
    const TABLE = "hideandseek";

    private Context $context;

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
        return $this->context->table(self::TABLE)->where("player_name = ?", $name);
    }

    /**
     * @param iterable $data
     * @param $id
     * @return int
     */
    public function updateRowById(iterable $data, $id)
    {
        return $this->context->table(self::TABLE)->wherePrimary($id)->update($data);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows(string $order = "exp DESC") {
        return $this->context->table(self::TABLE)->order($order);
    }
}
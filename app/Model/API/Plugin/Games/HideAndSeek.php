<?php


namespace App\Model\API\Plugin\Games;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * Class HideAndSeek
 * @package App\Model\API\Plugin
 */
class HideAndSeek
{
    const TABLE = "hideandseek";

    private Explorer $Explorer;

    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function getRowById($id) {
        return $this->Explorer->table(self::TABLE)->get($id);
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getRowByName($name) {
        return $this->Explorer->table(self::TABLE)->where("player_name = ?", $name);
    }

    /**
     * @param iterable $data
     * @param $id
     * @return int
     */
    public function updateRowById(iterable $data, $id)
    {
        return $this->Explorer->table(self::TABLE)->wherePrimary($id)->update($data);
    }

    /**
     * @param string $order
     * @return Selection
     */
    public function getAllRows(string $order = "exp DESC") {
        return $this->Explorer->table(self::TABLE)->order($order);
    }
}
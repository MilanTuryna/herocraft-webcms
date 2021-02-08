<?php


namespace App\Model\API\Plugin;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

/**
 * Class IConomy
 * @package App\Model\API\Plugin
 *
 * This class isn't registered in DI container, because constructor Explorer parameter is dynamic (survival iconomy, skyblock iconomy etc.)
 */
abstract class abstractIconomy
{
    private Explorer $explorer;
    private string $tableName;

    /**
     * IConomy constructor.
     * @param Explorer $explorer
     * @param string $tableName
     */
    public function __construct(Explorer $explorer, $tableName = "iconomy")
    {
        $this->explorer = $explorer;
        $this->tableName = $tableName;
    }

    public function getAllRows($order = "balance DESC") {
        return $this->explorer->table($this->tableName)->order($order);
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getRowByName($name) {
        return $this->explorer->table($this->tableName)->where("username = ?", $name);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getRowById(int $id) {
        return $this->explorer->table($this->tableName)->wherePrimary($id);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRowById(int $id) {
        return $this->explorer->table($this->tableName)->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return Selection
     */
    public function deleteRowByName($name) {
        return $this->explorer->table($this->tableName)->where("username = ?", $name);
    }

    /**
     * @param int $id
     * @return int
     */
    public function updateRowById(int $id, iterable $data) {
        return $this->explorer->table($this->tableName)->wherePrimary($id)->update($data);
    }
}
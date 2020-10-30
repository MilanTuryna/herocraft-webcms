<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class IConomy
 * @package App\Model\API\Plugin
 *
 * This class isn't registered in DI container, because constructor Context parameter is dynamic (survival iconomy, skyblock iconomy etc.)
 */
abstract class abstractIconomy
{
    private Context $context;
    private string $tableName;

    /**
     * IConomy constructor.
     * @param Context $context
     * @param string $tableName
     */
    public function __construct(Context $context, $tableName = "iconomy")
    {
        $this->context = $context;
        $this->tableName = $tableName;
    }

    public function getAllRows($order = "balance DESC") {
        return $this->context->table($this->tableName)->order($order);
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getRowByName($name) {
        return $this->context->table($this->tableName)->where("username = ?", $name);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getRowById(int $id) {
        return $this->context->table($this->tableName)->wherePrimary($id);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRowById(int $id) {
        return $this->context->table($this->tableName)->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return Selection
     */
    public function deleteRowByName($name) {
        return $this->context->table($this->tableName)->where("username = ?", $name);
    }

    /**
     * @param int $id
     * @return int
     */
    public function updateRowById(int $id, iterable $data) {
        return $this->context->table($this->tableName)->wherePrimary($id)->update($data);
    }
}
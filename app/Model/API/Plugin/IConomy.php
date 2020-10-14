<?php


namespace App\Model\API\Plugin;

use Nette\Database\Context;
use Nette\Database\Table\Selection;

/**
 * Class IConomy
 * @package App\Model\API\Plugin
 *
 * This class isn't registered in DI container, because constructor Context parameter is dynamic (survival ionomy, skyblock iconomy etc.)
 */
class IConomy
{
    private Context $context;

    const TABLE_NAME = "iconomy";

    /**
     * IConomy constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $name
     * @return Selection
     */
    public function getRowByName($name) {
        return $this->context->table(self::TABLE_NAME)->where("username = ?", $name);
    }

    /**
     * @param $id
     * @return Selection
     */
    public function getRowById(int $id) {
        return $this->context->table(self::TABLE_NAME)->wherePrimary($id);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRowById(int $id) {
        return $this->context->table(self::TABLE_NAME)->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return Selection
     */
    public function deleteRowByName($name) {
        return $this->context->table(self::TABLE_NAME)->where("username = ?", $name);
    }

    /**
     * @param int $id
     * @return Selection
     */
    public function updateRowById(int $id) {
        return $this->context->table(self::TABLE_NAME)->wherePrimary($id);
    }
}
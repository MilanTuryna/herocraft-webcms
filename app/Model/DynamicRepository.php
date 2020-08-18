<?php


namespace App\Model;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\IRow;

/**
 * Class DynamicRepository
 * @package App\Model
 */
class DynamicRepository
{
    private Context $context;
    private string $table;

    /**
     * DynamicRepository constructor.
     * @param Context $context
     * @param string $table
     */
    public function __construct(Context $context, string $table)
    {
        $this->context = $context;
        $this->table = $table;
    }

    /**
     * @param $id
     * @return ActiveRow|null
     */
    public function findById($id): ActiveRow {
        return $this->context->table($this->table)->get($id);
    }

    /**
     * @return array
     */
    public function findAll(): array {
        return $this->context->table($this->table)->fetchAll();
    }

    /**
     * @param array $values
     */
    public function create(array $values): void {
        $this->context->table($this->table)->insert($values);
    }

    /**
     * @param string $where
     * @param $value
     * @param array $values
     */
    public function update(string $where,$value,array $values): void {
        $this->context->table($this->table)->where($where,$value)->update($values);
    }

    /**
     * @param string $where
     * @param $value
     * @return bool
     */
    public function delete(string $where, $value): bool {
        return (bool)$this->context->table($this->table)->where($where, $value)->delete();
    }

    /**
     * @param string $where
     * @param $value
     * @return bool
     */
    public function isDuplicated(string $where, $value): bool {
        return (bool)$this->context->table($this->table)->where($where, $value)->count('*');
    }
}
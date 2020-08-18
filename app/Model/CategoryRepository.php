<?php


namespace App\Model;

use Nette;
use Nette\Database\Context;

/**
 * Class CategoryRepository
 * @package App\Model
 */
class CategoryRepository
{
    use Nette\SmartObject;

    private Context $context;

    /**
     * CategoryRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryById($id) {
        return $this->context->table('categories')->get($id);
    }

    /**
     * @return array|Nette\Database\Table\IRow[]
     */
    public function findCategories() {
        return $this->context->table('categories')->fetchAll();
    }

    /**
     * @param $name
     * @param $color
     */
    public function addCategory($name, $color): void {
        $this->context->table('categories')->insert([
            "name" => $name,
            "color" => $color
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool {
        return (bool)$this->context->table('categories')->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return bool
     */
    public function isDuplicated($name): bool {
        return (bool)$this->context->table('categories')->where('name = ?', $name)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updateCategory($id, array $values): void {
        $this->context->table('categories')->wherePrimary($id)->update($values);
    }

    /**
     * @param string $name
     * @return Nette\Database\IRow|Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryByName(string $name) {
        return $this->context->table('categories')
            ->where('name = ?', $name)
            ->fetch();
    }
}
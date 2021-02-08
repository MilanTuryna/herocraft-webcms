<?php


namespace App\Model;

use Nette;
use Nette\Database\Explorer;

/**
 * Class CategoryRepository
 * @package App\Model
 */
class CategoryRepository
{
    use Nette\SmartObject;

    private Explorer $explorer;

    /**
     * CategoryRepository constructor.
     * @param Explorer $explorer
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryById($id) {
        return $this->explorer->table('categories')->get($id);
    }

    /**
     * @return array|Nette\Database\Table\Row[]
     */
    public function findCategories() {
        return $this->explorer->table('categories')->fetchAll();
    }

    /**
     * @param $name
     * @param $color
     */
    public function addCategory($name, $color): void {
        $this->explorer->table('categories')->insert([
            "name" => $name,
            "color" => $color
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool {
        return (bool)$this->explorer->table('categories')->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return bool
     */
    public function isDuplicated($name): bool {
        return (bool)$this->explorer->table('categories')->where('name = ?', $name)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updateCategory($id, array $values): void {
        $this->explorer->table('categories')->wherePrimary($id)->update($values);
    }

    /**
     * @param string $name
     * @return Nette\Database\Row|Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryByName(string $name) {
        return $this->explorer->table('categories')
            ->where('name = ?', $name)
            ->fetch();
    }
}
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

    private Explorer $Explorer;

    /**
     * CategoryRepository constructor.
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer)
    {
        $this->Explorer = $Explorer;
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryById($id) {
        return $this->Explorer->table('categories')->get($id);
    }

    /**
     * @return array|Nette\Database\Table\Row[]
     */
    public function findCategories() {
        return $this->Explorer->table('categories')->fetchAll();
    }

    /**
     * @param $name
     * @param $color
     */
    public function addCategory($name, $color): void {
        $this->Explorer->table('categories')->insert([
            "name" => $name,
            "color" => $color
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool {
        return (bool)$this->Explorer->table('categories')->wherePrimary($id)->delete();
    }

    /**
     * @param $name
     * @return bool
     */
    public function isDuplicated($name): bool {
        return (bool)$this->Explorer->table('categories')->where('name = ?', $name)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updateCategory($id, array $values): void {
        $this->Explorer->table('categories')->wherePrimary($id)->update($values);
    }

    /**
     * @param string $name
     * @return Nette\Database\Row|Nette\Database\Table\ActiveRow|null
     */
    public function findCategoryByName(string $name) {
        return $this->Explorer->table('categories')
            ->where('name = ?', $name)
            ->fetch();
    }
}
<?php


namespace App\Model;

use Nette;
use Nette\Database\Explorer;

/**
 * Class PageRepository
 * @package App\Model
 */
class PageRepository
{
    use Nette\SmartObject;

    private Explorer $explorer;

    /**
     * PageRepository constructor.
     * @param Explorer $explorer
     */
    public function __construct(Explorer $explorer) {
        $this->explorer = $explorer;
    }

    /**
     * @return array|Nette\Database\Table\Row[]
     */
    public function findPages() {
        return $this->explorer->table('pages')->fetchAll();
    }

    /**
     * @param $url
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageByUrl($url) {
        return $this->explorer->table('pages')->where('url = ?', $url)->fetch();
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageById($id) {
        return $this->explorer->table('pages')->get($id);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isDuplicated($url): bool {
        return (bool)$this->explorer->table('pages')->where('url = ?', $url)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updatePage($id, array $values): void {
        $this->explorer->table('pages')->wherePrimary($id)->update($values);
    }

    public function createPage($values): void {
        $this->explorer->table('pages')->insert($values);
    }

    /**
     * Returning true if page was deleted
     * @param $url
     * @return bool
     */
    public function deletePage($url): bool {
        return (bool)$this->explorer->table('pages')->where('url = ?', $url)->delete();
    }
}
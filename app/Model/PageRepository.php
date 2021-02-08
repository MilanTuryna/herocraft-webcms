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

    private Explorer $Explorer;

    /**
     * PageRepository constructor.
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer) {
        $this->Explorer = $Explorer;
    }

    /**
     * @return array|Nette\Database\Table\Row[]
     */
    public function findPages() {
        return $this->Explorer->table('pages')->fetchAll();
    }

    /**
     * @param $url
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageByUrl($url) {
        return $this->Explorer->table('pages')->where('url = ?', $url)->fetch();
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageById($id) {
        return $this->Explorer->table('pages')->get($id);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isDuplicated($url): bool {
        return (bool)$this->Explorer->table('pages')->where('url = ?', $url)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updatePage($id, array $values): void {
        $this->Explorer->table('pages')->wherePrimary($id)->update($values);
    }

    public function createPage($values): void {
        $this->Explorer->table('pages')->insert($values);
    }

    /**
     * Returning true if page was deleted
     * @param $url
     * @return bool
     */
    public function deletePage($url): bool {
        return (bool)$this->Explorer->table('pages')->where('url = ?', $url)->delete();
    }
}
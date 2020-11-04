<?php


namespace App\Model;

use Nette;
use Nette\Database\Context;

/**
 * Class PageRepository
 * @package App\Model
 */
class PageRepository
{
    use Nette\SmartObject;

    private Context $context;

    /**
     * PageRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context) {
        $this->context = $context;
    }

    /**
     * @return array|Nette\Database\Table\IRow[]
     */
    public function findPages() {
        return $this->context->table('pages')->fetchAll();
    }

    /**
     * @param $url
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageByUrl($url) {
        return $this->context->table('pages')->where('url = ?', $url)->fetch();
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow
     */
    public function findPageById($id) {
        return $this->context->table('pages')->get($id);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isDuplicated($url): bool {
        return (bool)$this->context->table('pages')->where('url = ?', $url)->count('*');
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updatePage($id, array $values): void {
        $this->context->table('pages')->wherePrimary($id)->update($values);
    }

    public function createPage($values): void {
        $this->context->table('pages')->insert($values);
    }

    /**
     * Returning true if page was deleted
     * @param $url
     * @return bool
     */
    public function deletePage($url): bool {
        return (bool)$this->context->table('pages')->where('url = ?', $url)->delete();
    }
}
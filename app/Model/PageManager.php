<?php


namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

/**
 * Class PageManager
 * @package App\Model
 */
class PageManager
{
    use Nette\SmartObject;

    private Explorer $db;
    const
        TABLE_NAME = 'pages',
        COLUMN_URL = 'url';

    /**
     * PageManager constructor.
     * @param Explorer $db
     */
    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    /**
     * @param $url
     * @return Nette\Database\Row|ActiveRow|null
     */
    public function getPage($url) {
        return $this->db->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->fetch();
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->db->table(self::TABLE_NAME)->fetchAll();
    }

    /**
     * @param $url
     */
    public function removePage($url): void {
        $this->db->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->delete();
    }
}
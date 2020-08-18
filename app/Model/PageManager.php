<?php


namespace App\Model;

use Nette;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

/**
 * Class PageManager
 * @package App\Model
 */
class PageManager
{
    use Nette\SmartObject;

    private Context $db;
    const
        TABLE_NAME = 'pages',
        COLUMN_URL = 'url';

    /**
     * PageManager constructor.
     * @param Context $db
     */
    public function __construct(Context $db)
    {
        $this->db = $db;
    }

    /**
     * @param $url
     * @return Nette\Database\IRow|ActiveRow|null
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
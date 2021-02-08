<?php


namespace App\Model;

use Exception;
use Nette;
use Nette\Utils\FileSystem;
use Nette\Database\Explorer;

/**
 * Class ArticleRepository
 * @package App\Model
 */
class ArticleRepository
{
    use Nette\SmartObject;

    /**
     * @var Explorer
     */
    private Explorer $Explorer;

    const PATH = 'img/miniatures/';


    /**
     * ArticleRepository constructor.
     * @param Explorer $Explorer
     */
    public function __construct(Explorer $Explorer) {
        $this->Explorer = $Explorer;
     }

    /**
     * @param null|int $limit
     * @param string $sort
     * @return Nette\Database\Table\Selection
     * @throws Exception
     */
    public function findPublishedArticles($limit = null, $sort = "DESC")
    {
        return $this->Explorer->table('articles')
            ->where('created_at < ', new \DateTime)
            ->order('created_at ' . $sort)
            ->limit($limit);
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return Nette\Database\ResultSet
     */
    public function findArticlesWithCategory(int $limit = 0, int $offset = null) {
        $prepareLimit = ($limit !== null ? "LIMIT " . $limit : "");
        $prepareOffset = ($offset !== null ? "OFFSET " . $offset : "");
        return $this->Explorer->query("SELECT articles.*, categories.name as category_name, categories.color as category_color FROM articles LEFT JOIN categories ON articles.category_id = categories.id ORDER BY created_at DESC ".$prepareLimit." ".$prepareOffset);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getPublishedArticlesCount(): int
    {
        return $this->Explorer->fetchField('SELECT COUNT(*) FROM articles WHERE created_at < ?', new \DateTime);
    }

    /**
     * @param $url
     * @return Nette\Database\Row|Nette\Database\Table\ActiveRow|null
     */
    public function findArticleByUrl($url) {
        return $this->Explorer->table('articles')
            ->where('url = ?', $url)
            ->fetch();
    }



    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow|null
     */
    public function findArticleById($id): Nette\Database\Table\ActiveRow {
        return $this->Explorer->table('articles')->get($id);
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updateArticle($id, array $values): void {
            $this->Explorer->table('articles')->wherePrimary($id)->update($values);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isDuplicated($url): bool {
        return (bool)$this->Explorer->table('articles')->where('url = ?', $url)->count('*');
    }

    /**
     * @param array $values
     * @return bool|int|Nette\Database\Table\ActiveRow
     */
    public function createArticle(array $values): Nette\Database\Table\ActiveRow {
        return $this->Explorer->table('articles')->insert($values);
    }

    /**
     * @param $id
     * @param Nette\Http\FileUpload $file
     */
    public function setMiniature($id, Nette\Http\FileUpload $file): void {
        $suffix = pathinfo($file->getName(), PATHINFO_EXTENSION);
        $path = self::PATH . "{$id}.{$suffix}";
        $file->move($path);
    }

    /**
     * @param $id
     * @return array
     */
    public function getMiniature($id): array {
        return glob(self::PATH . "{$id}.*");
    }

    /**
     * @param $id
     */
    public function deleteMiniature($id): void {
        $files = glob(self::PATH . "{$id}.*");
        foreach($files as $file) {
            FileSystem::delete($file);
        }
    }

    /**
     * @param $url
     * @return bool
     */
    public function deleteArticle($url): bool {
        $row = $this->Explorer->table('articles')->where('url = ?', $url)->fetch();
        $deleted = (bool)$this->Explorer->table('articles')->where('url = ?', $url)->delete();
        $this->deleteMiniature($row->id);

        return $deleted;
    }

    /**
     * @return CategoryRepository
     */
    public function getCategoryRepository(): CategoryRepository {
        return new CategoryRepository($this->Explorer);
    }
}
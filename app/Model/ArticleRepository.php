<?php


namespace App\Model;

use Nette;
use Nette\Utils\FileSystem;
use Nette\Database\Context;

/**
 * Class ArticleRepository
 * @package App\Model
 */
class ArticleRepository
{
    use Nette\SmartObject;

    /**
     * @var Context
     */
    private Context $context;

    const PATH = 'img/miniatures/';
    const PROPERTIES = [
        "archiv_length" => 350, // ukázka článků v archivu
        "per_page" => 5,
        "min_length" => 420,
        "sorting" => 'ASC',
        "MINIATURE" => [
            "width" => 1680,
            "height" => 720,
        ]
    ];


    /**
     * ArticleRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context) {
        $this->context = $context;
     }

    /**
     * @param null|int $limit
     * @param string $sort
     * @return Nette\Database\Table\Selection
     * @throws \Exception
     */
    public function findPublishedArticles($limit = null, $sort = self::PROPERTIES['sorting'])
    {
        return $this->context->table('articles')
            ->where('created_at < ', new \DateTime)
            ->order('created_at ' . $sort)
            ->limit($limit);
    }

    /**
     * @param $url
     * @return Nette\Database\IRow|Nette\Database\Table\ActiveRow|null
     */
    public function findArticleByUrl($url) {
        return $this->context->table('articles')
            ->where('url = ?', $url)
            ->fetch();
    }

    /**
     * @param $id
     * @return Nette\Database\Table\ActiveRow|null
     */
    public function findArticleById($id): Nette\Database\Table\ActiveRow {
        return $this->context->table('articles')->get($id);
    }

    /**
     * @param $id
     * @param array $values
     */
    public function updateArticle($id, array $values): void {
            $this->context->table('articles')->wherePrimary($id)->update($values);
    }

    /**
     * @param $url
     * @return bool
     */
    public function isDuplicated($url): bool {
        return (bool)$this->context->table('articles')->where('url = ?', $url)->count('*');
    }

    /**
     * @param array $values
     * @return bool|int|Nette\Database\Table\ActiveRow
     */
    public function createArticle(array $values): Nette\Database\Table\ActiveRow {
        return $this->context->table('articles')->insert($values);
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
        $row = $this->context->table('articles')->where('url = ?', $url)->fetch();
        $deleted = (bool)$this->context->table('articles')->where('url = ?', $url)->delete();
        $this->deleteMiniature($row->id);

        return $deleted;
    }

    /**
     * @return CategoryRepository
     */
    public function getCategoryRepository(): CategoryRepository {
        return new CategoryRepository($this->context);
    }
}
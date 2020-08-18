<?php


namespace App\Model;

use App\Model\API\Status;
use Nette\Caching\IStorage;
use Nette\Database\Context;
use Nette\Caching\Cache;
use Nette\Database\Table\ActiveRow;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;

/**
 * Class Settings
 * @package App\Model
 */
class SettingsRepository
{
    const PATH = 'img/';

    Private Context $db;
    Private Cache $cache;

    /**
     * Settings constructor.
     * @param Context $db
     * @param IStorage $storage
     */
    public function __construct(Context $db, IStorage $storage)
    {
        $this->db = $db;
        $this->cache = new Cache($storage);
    }

    /**
     * @param $arr
     */
    public function setRows($arr) {
        $this->db->table('nastaveni')->where('id', 1)->update($arr);
    }

    public function getLogo(): array {
        return glob(self::PATH . "logo.*");
    }

    public function setLogo(FileUpload $file) {
        $suffix = pathinfo($file->getName(), PATHINFO_EXTENSION);
        $path = self::PATH . "logo.{$suffix}";
        $file->move($path);
    }

    public function deleteLogo() {
        $files = glob(self::PATH . "logo.*");
        foreach($files as $file) {
            FileSystem::delete($file);
        }
    }

    /**
     * @return ActiveRow|null
     */
    public function getAllRows() {
        return $this->db->table('nastaveni')->get(1);
    }

    public function getStatus($ip) {
        return new Status($ip,$this->cache);
    }

    /**
     * @param $name
     * @return ActiveRow|null
     */
    public function getRow($name) {
        return $this->db->table('nastaveni')->select($name)->get(1);
    }
}
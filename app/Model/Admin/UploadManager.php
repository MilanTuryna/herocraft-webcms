<?php


namespace App\Model\Admin;


use Nette\FileNotFoundException;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

/**
 * Class UploadManager
 * @package App\Model\Admin
 */
class UploadManager
{
    const PATH = "upload/";

    /**
     * @param callable $errorCallback
     * @param FileUpload[] $fileUploads
     */
    public static function add(callable $errorCallback, array $fileUploads) {
        $errorUploads = [];
        foreach ($fileUploads as $upload) {
            if($upload->hasFile() && $upload->isOk() && $upload->isImage()) {
                $upload->move(self::PATH . uniqid() . "." . pathinfo($upload->getName(), PATHINFO_EXTENSION));
            } else {
                array_push($errorUploads, $upload);
            }
        }

        $errorCallback($errorUploads);
    }

    /**
     * @return Finder
     */
    public static function getUploads() {
        return Finder::findFiles("*")->from(self::PATH);
    }

    /**
     * @param $name
     */
    public static function deleteUpload($name) {
        if(file_exists(self::PATH . $name)) {
            FileSystem::delete(self::PATH . $name);
        } else {
            throw new FileNotFoundException();
        }
    }
}
<?php


namespace App\Model\WebLoader;


use Nette\Utils\Finder;
use SplFileInfo;

/**
 * Class FileMask
 * @package App\Model\WebLoader
 */
class FileMask
{
    private string $mainDir;
    private array $masks;
    /**
     * Mask constructor.
     * @param string $mainDir
     * @param array $masks
     */
    public function __construct(string $mainDir, array $masks)
    {
        $this->mainDir = $mainDir;
        $this->masks = array_map(fn($x) => $x, $masks);
    }

    /**
     * @return string
     */
    public function scrapFiles(): string {
        $files = Finder::findFiles($this->masks)->from($this->mainDir);
        $content = "";
        /** @var SplFileInfo $fileInfo */
        foreach ($files as $fileInfo) {
            if($fileInfo->isReadable() && $fileInfo->getSize() > 0) {
                $fileObject = $fileInfo->openFile();
                $content .= $fileObject->fread($fileObject->getSize());
            }
        }
        return $content;
    }

    /**
     * @return array
     */
    public function getMasks(): array
    {
        return $this->masks;
    }
}
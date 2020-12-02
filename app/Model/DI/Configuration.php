<?php


namespace App\Model\DI;


use Nette\IOException;
use Nette\Utils\FileSystem;

/**
 * Class Configuration
 * @package App\Model\DI
 */
class Configuration
{
    private string $tempDir;

    /**
     * Configuration constructor.
     * @param string $tempDir
     */
    public function __construct(string $tempDir)
    {
        $this->tempDir = $tempDir;
    }

    /**
     * @throws IOException
     */
    public function update(): void {
        FileSystem::delete($this->tempDir);
    }

    /**
     * @return string
     */
    public function getTempDir(): string
    {
        return $this->tempDir;
    }
}
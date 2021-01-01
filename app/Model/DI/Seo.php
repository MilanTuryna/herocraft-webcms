<?php


namespace App\Model\DI;

use Nette\SmartObject;

/**
 * Class Seo
 * @package App\Model\DI
 */
final class Seo
{
    use SmartObject;

    private ?string $og_image; // image url

    /**
     * Seo constructor.
     * @param string $og_image
     */
    public function __construct(string $og_image)
    {
        $this->og_image = isset($og_image) ? $og_image : null;
    }

    /**
     * @return string|null
     */
    public function getOgImage(): ?string
    {
        return $this->og_image;
    }
}
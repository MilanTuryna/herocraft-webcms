<?php


namespace App\Model\Front\UI\Elements;

use App\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Image
 * @package App\Model\Front\UI
 */
class Image implements IElement
{
    use SmartObject;

    const DEF_WIDTH = "100%";
    const DEF_HEIGHT = "auto";

    public string $url;
    public string $width;
    public string $height;
    public string $align;
    public string $alt;

    /**
     * Image constructor.
     * @param string $url
     * @param string $align
     * @param string $width
     * @param string $height
     * @param string|null $alt
     */
    public function __construct(string $url,
                                string $align,
                                string $width = self::DEF_WIDTH,
                                string $height = self::DEF_HEIGHT,
                                string $alt = '')
    {
        $this->url = $url;
        $this->align = $align;
        $this->width = $width;
        $this->height = $height;
        $this->alt = $alt;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'align' => $this->align,
            'width' => $this->width,
            'height' => $this->height,
            'alt' => $this->alt
        ];
    }

    /**
     * @return string
     */
    public function getElementName(): string
    {
        return "image";
    }
}
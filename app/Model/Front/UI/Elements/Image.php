<?php


namespace App\Model\Front\UI\Elements;

use App\Model\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Image
 * @package App\Model\Front\UI
 */
class Image implements IElement
{
    use SmartObject;

    private string $url;
    private string $width = "100%";
    private string $height = "auto";
    private string $title;
    private ?string $alt;

    /**
     * Image constructor.
     * @param string $title
     * @param string $url
     * @param string $alt
     */
    public function __construct(string $title, string $url, string $alt)
    {
        $this->title = $title;
        $this->url = $url;
        $this->alt = $alt;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode(get_object_vars($this)) ?: "{}";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJSON();
    }
}
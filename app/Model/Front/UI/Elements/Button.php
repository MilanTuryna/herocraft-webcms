<?php

namespace App\Model\Front\UI\Elements;

use App\Model\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Button
 * @package App\Model\Front\UI
 * Representing a button for dynamic UI
 */
class Button implements IElement
{
    use SmartObject;

    private Text $title;
    private string $link;
    private string $bgColor = "#eeeeee";
    private string $width = "auto";
    private string $target = "_self";

    /**
     * Button constructor.
     * @param Text $title
     * @param string $link
     */
    public function __construct(Text $title, string $link)
    {
        $this->title = $title;
        $this->link = $link;
    }

    /**
     * @param string $bgColor
     */
    public function setBgColor(string $bgColor): void
    {
        $this->bgColor = $bgColor;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * @param string $width
     */
    public function setWidth(string $width): void
    {
        $this->width = $width;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode(get_object_vars($this)) ?: "{}";
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJSON();
    }

    /**
     * @return IElement
     * @inheritDoc
     */
    public static function example(): Button
    {
        return new Button(new Text("Example button"), "#");
    }
}
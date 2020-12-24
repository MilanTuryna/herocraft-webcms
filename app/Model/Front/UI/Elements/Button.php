<?php

namespace App\Model\Front\UI;

use Nette\SmartObject;

/**
 * Class Button
 * @package App\Model\Front\UI
 * Representing a button for dynamic UI
 */
class Button implements IElement
{
    use SmartObject;

    private string $title;
    private string $link;
    private string $textColor = "#000000";
    private string $bgColor = "#eeeeee";
    private string $width = "auto";
    private string $target = "_self";

    /**
     * Button constructor.
     * @param string $title
     * @param string $link
     */
    public function __construct(string $title, string $link)
    {
        $this->title = $title;
        $this->link = $link;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor(string $textColor): void
    {
        $this->textColor = $textColor;
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
     */
    public function toJSON(): string
    {
        return json_encode(get_object_vars($this)) ?: "{}";
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->toJSON();
    }
}
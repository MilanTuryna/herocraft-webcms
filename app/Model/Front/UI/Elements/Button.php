<?php

namespace App\Model\Front\UI\Elements;

use App\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Button
 * @package App\Model\Front\UI
 * Representing a button for dynamic UI
 */
class Button implements IElement
{
    use SmartObject;

    const DEF_WIDTH = "auto";
    const DEF_TARGET = "_self";
    const DEF_BG_COLOR = "#eeeeee";

    public Text $title;
    public string $link;
    public string $bgColor;
    public string $width;
    public string $target;
    public string $style;

    /**
     * Button constructor.
     * @param Text $title
     * @param string $link
     * @param string $style
     * @param string $target
     * @param string $width
     * @param string $bgColor
     */
    public function __construct(Text $title,
                                string $link,
                                string $style,
                                string $target = self::DEF_TARGET,
                                string $width = self::DEF_WIDTH,
                                string $bgColor = self::DEF_BG_COLOR)
    {
        $this->title = $title;
        $this->link = $link;
        $this->target = $target;
        $this->width = $width;
        $this->bgColor = $bgColor;
        $this->style = $style;
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function toArray(): array
    {
        return [
            "title" => [
                'color' => $this->title->color,
                'content' => $this->title->content,
            ],
            'link' => [
                'url' => $this->link,
                'target' => $this->target
            ],
            'width' => $this->width,
            'bgColor' => $this->bgColor,
            'style' => $this->style,
        ];
    }

    public function getElementName(): string
    {
        return "button";
    }
}
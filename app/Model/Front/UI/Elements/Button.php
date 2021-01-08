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
    public ?string $css;

    /**
     * Button constructor.
     * @param Text $title
     * @param string $link
     * @param string $target
     * @param string $width
     * @param string $bgColor
     * @param string|null $css
     */
    public function __construct(Text $title,
                                string $link,
                                string $target = self::DEF_TARGET,
                                string $width = self::DEF_WIDTH,
                                string $bgColor = self::DEF_BG_COLOR,
                                string $css = null)
    {
        $this->title = $title;
        $this->link = $link;
        $this->target = $target;
        $this->width = $width;
        $this->bgColor = $bgColor;
        $this->css = $css;
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
            'css' => $this->css,
        ];
    }
}
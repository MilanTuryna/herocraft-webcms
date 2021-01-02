<?php

namespace App\Model\Front\UI\Elements;

use Nette\SmartObject;

/**
 * Class Button
 * @package App\Model\Front\UI
 * Representing a button for dynamic UI
 */
class Button
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

    /**
     * Button constructor.
     * @param Text $title
     * @param string $link
     * @param string $target
     * @param string $width
     * @param string $bgColor
     */
    public function __construct(Text $title,
                                string $link,
                                string $target = self::DEF_TARGET,
                                string $width = self::DEF_WIDTH,
                                string $bgColor = self::DEF_BG_COLOR)
    {
        $this->title = $title;
        $this->link = $link;
        $this->target = $target;
        $this->width = $width;
        $this->bgColor;
    }
}
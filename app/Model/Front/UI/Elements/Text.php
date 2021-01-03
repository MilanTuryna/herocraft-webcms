<?php


namespace App\Model\Front\UI\Elements;

use App\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Text
 * @package App\Model\Front\UI
 */
class Text implements IElement
{
    use SmartObject;

    public string $content;
    public string $color;

    /**
     * Text constructor.
     * @param string $content
     * @param string $color
     */
    public function __construct(string $content, string $color = "#000000")
    {
        $this->content = $content;
        $this->color = $color;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return ["content" => $this->content, "color" => $this->color];
    }
}
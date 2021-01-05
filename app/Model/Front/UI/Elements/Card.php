<?php


namespace App\Model\Front\UI\Elements;

use App\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Card
 * @package App\Model\Front\UI
 */
class Card implements IElement
{
    use SmartObject;

    public string $title;
    public Text $text;
    public string $align;

    /**
     * Card constructor.
     * @param string $title
     * @param Text $text
     * @param string $align
     */
    public function __construct(string $title, Text $text, string $align)
    {
        $this->title = $title;
        $this->text = $text;
        $this->align = $align;
    }

    /**
     * @return array
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['title' => $this->title, 'text' => $this->text, "align" => $this->align];
    }
}
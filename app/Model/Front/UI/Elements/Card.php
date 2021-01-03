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

    private string $title;
    private Text $text;

    /**
     * Card constructor.
     * @param string $title
     * @param Text $text
     */
    public function __construct(string $title, Text $text)
    {
        $this->title = $title;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Text
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @return array
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['title' => $this->title, 'text' => $this->text];
    }
}
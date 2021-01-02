<?php


namespace App\Model\Front\UI\Elements;

use Nette\SmartObject;

/**
 * Class Card
 * @package App\Model\Front\UI
 */
class Card
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
}
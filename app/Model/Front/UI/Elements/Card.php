<?php


namespace App\Model\Front\UI;


use Nette\SmartObject;

/**
 * Class Card
 * @package App\Model\Front\UI
 */
class Card
{
    use SmartObject;

    private string $title;
    private string $text;

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
     * @return Text|string
     */
    public function getText()
    {
        return $this->text;
    }
}
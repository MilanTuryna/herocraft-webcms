<?php


namespace App\Model\Front\UI\Elements;

use App\Model\Front\UI\IElement;
use Nette\SmartObject;

/**
 * Class Text
 * @package App\Model\Front\UI
 */
class Text implements IElement
{
    use SmartObject;

    private string $content;
    private string $color;

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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getWordCount(): string {
        return str_word_count($this->content);
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return string
     * @inheritDoc
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
     * @inheritDoc
     * @return IElement
     */
    public static function example(): Text
    {
        return new Text("Lorem ipsum dolor sit...");
    }
}
<?php


namespace App\Model\Front\UI;

/**
 * Class Text
 * @package App\Model\Front\UI
 */
class Text
{
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
    public function getColor(): string
    {
        return $this->color;
    }
}
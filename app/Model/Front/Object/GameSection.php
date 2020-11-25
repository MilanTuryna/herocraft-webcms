<?php

namespace App\Model\Front\Object;

/**
 * Class GameSection
 * @package App\Model\DI
 */
class GameSection
{
    const defColor = "#e0f2f1";
    private string $description, $link, $title, $color;

    /**
     * GameSection constructor.
     * @param string $title
     * @param string $description
     * @param string $link
     * @param string $color
     */
    public function __construct(string $title, string $description, string $link, string $color)
    {
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
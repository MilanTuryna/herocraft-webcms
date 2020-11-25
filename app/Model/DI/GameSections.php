<?php


namespace App\Model\DI;

use App\Model\Front\Object\GameSection;

/**
 * Class GameSections
 * @package App\Model\DI
 */
class GameSections {
    private array $sections;

    /**
     * GameSections constructor.
     * @param $sections
     */
    public function __construct(array $sections)
    {
        $this->sections = $sections;
    }

    /**
     * @return GameSection[]
     */
    public function getSections(): array {
        return array_map(function($sc) {
            return new GameSection($sc['title'], $sc['description'], $sc['link'],
                isset($sc['color']) && !empty($sc['color']) ? $sc['color'] : GameSection::defColor);
        }, $this->sections);
    }

    /**
     * @return int
     */
    public function getCount(): int {
        return count($this->sections);
    }
}
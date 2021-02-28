<?php


namespace App\Model\Front\UI\Parts;


use Nette\SmartObject;

/**
 * Class Widget
 * @package App\Model\Front\UI\Parts
 */
class Widget
{
    use SmartObject;

    const LEFT_SIDE = 'left';
    const RIGHT_SIDE = 'right';
    const SIDES = [
        self::LEFT_SIDE => 'Levá strana',
        self::RIGHT_SIDE => 'Pravá strana'
    ];

    public string $name;
    public string $html;
    public string $side;
    public string $description;

    /** Non-required properties from database */
    public ?string $dbId = null;

    /**
     * Widget constructor.
     * @param string $name
     * @param string $html
     * @param string $side
     * @param string $description
     */
    public function __construct(string $name, string $html, string $side, string $description = '')
    {
        $this->name = $name;
        $this->html = $html;
        $this->side = $side;
        $this->description = $description;
    }

    /**
     * @param bool $readable
     * @return string
     */
    public function getSide(bool $readable = false): string {
        return !$readable ? $this->side : self::SIDES[$this->side];
    }
}
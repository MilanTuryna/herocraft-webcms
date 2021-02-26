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

    public string $name;
    public string $html;
    public string $side;
    public string $description;

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
}
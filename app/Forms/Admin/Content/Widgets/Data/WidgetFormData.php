<?php

namespace App\Forms\Admin\Content\Widgets\Data;

use App\Model\Front\UI\Parts\Widget;

/**
 * Class WidgetFormData
 * @package App\Forms\Admin\Content\Widgets\Data
 */
class WidgetFormData
{
    const SIDES = [
        Widget::LEFT_SIDE => 'Zařadit na levou stranu',
        Widget::RIGHT_SIDE => 'Zařadit na pravou stranu'
    ];

    public string $name;
    public string $description = '';
    public string $html;
    public string $side;

    /**
     * @return Widget
     */
    public function getWidget(): Widget {
        return new Widget($this->name, $this->html, $this->side, $this->description);
    }
}
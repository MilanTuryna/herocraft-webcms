<?php


namespace App\Front;


use App\Model\Front\UI\Parts\Widget;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

/**
 * Class WidgetRepository
 * @package App\Front
 */
class WidgetRepository
{
    const TABLE = 'widgets';

    private Explorer $explorer;

    /**
     * WidgetRepository constructor.
     * @param Explorer $explorer
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param Widget $widget
     * @return iterable
     */
    public static function getIterableRow(Widget $widget): iterable {
        return [
            "name" => $widget->name,
            "description" => $widget->description,
            "side" => $widget->side,
            "html" => $widget->html
        ];
    }

    /**
     * @param Widget $widget
     * @return bool|int|ActiveRow
     */
    public function createWidget(Widget $widget) {
        return $this->explorer->table(self::TABLE)->insert(self::getIterableRow($widget));
    }

    /**
     * @param Widget $widget
     * @return int
     */
    public function updateWidget(Widget $widget): int {
        return $this->explorer->table(self::TABLE)->update(self::getIterableRow($widget));
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteWidget(int $id) {
        return $this->explorer->table(self::TABLE)->where('id = ?', $id)->delete();
    }

    /**
     * @return array|ActiveRow[]
     */
    public function getRightWidgets(): array {
        return $this->explorer->table(self::TABLE)->where("side = ?", Widget::RIGHT_SIDE)->fetchAll();
    }

    public function getLeftWidgets(): array {
        return $this->explorer->table(self::TABLE)->where("side = ?", Widget::LEFT_SIDE)->fetchAll();
    }

    /**
     * @return Explorer
     */
    public function getExplorer(): Explorer
    {
        return $this->explorer;
    }
}
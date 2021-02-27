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
     * @param ActiveRow $activeRow
     * @return Widget
     */
    public function parseWidget(ActiveRow $activeRow): Widget {
        $widget = new Widget($activeRow->name, $activeRow->name, $activeRow->side, $activeRow->description);
        $widget->dbId = $activeRow->id;
        return $widget;
    }

    /**
     * @param array $rows
     * @return array
     */
    public function rowsToWidgetList(array $rows): array {
        $widgetList = [];
        foreach ($rows as $row) {
            if(!($row instanceof ActiveRow)) throw new \TypeError("Please, use ActiveRow[] array in " .__METHOD__. " method");
            $widgetList[] = $this->parseWidget($row);
        }
        return $widgetList;
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
     * @param int $id
     * @return ActiveRow|null
     */
    public function getWidgetById(int $id): ?ActiveRow {
        return $this->explorer->table(self::TABLE)->where('id = ?', $id)->fetch();
    }

    /**
     * @return array|ActiveRow[]
     */
    public function getAllWidgets(): array {
        return $this->explorer->table(self::TABLE)->fetchAll();
    }

    /**
     * @return array|ActiveRow[]
     */
    public function getRightWidgets(): array {
        return $this->explorer->table(self::TABLE)->where("side = ?", Widget::RIGHT_SIDE)->fetchAll();
    }

    /**
     * @return array|ActiveRow[]
     */
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
<?php

namespace App\Front\Styles;

use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Response;
use Nette\SmartObject;
use TypeError;

/**
 * Class ButtonStyles
 * @package App\Front\Styles
 */
class ButtonStyles
{
    use SmartObject;

    const DB_TABLE = "button_styles";

    private Explorer $explorer;
    private Response $response;

    /**
     * ButtonStyles constructor.
     * @param Explorer $explorer
     */
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;
    }

    /**
     * @param ActiveRow[] $styles
     * @return array
     */
    public static function getSelectBox(array $styles) {
        $arr = [];
        foreach ($styles as $style) {
            if(!($style instanceof ActiveRow)) throw new TypeError("Please, pass ActiveRow[] from " . self::DB_TABLE . " table as parameter 'styles'");
            $arr[$style->class] = $style->name;
        }
        return $arr;
    }

    /**
     * @param $class
     * @return string
     */
    public static function parseClass($class): string {
        return str_starts_with($class, ".") ? substr($class, 1) : $class;
    }

    /**
     * @param $name
     * @param $class
     * @param $css
     * @return iterable
     */
    public static function getIterableRow($name, $class, $css): iterable {
        return [
            "name" => $name,
            "class" => self::parseClass($class),
            "css" => $css
        ];
    }

    /**
     * @return array|Row[]
     */
    public function getStyles(): array {
        return $this->explorer->table(self::DB_TABLE)->fetchAll();
    }

    /**
     * @param int $id
     * @return Row|ActiveRow|null
     */
    public function getStyleById(int $id): ?ActiveRow {
        return $this->explorer->table(self::DB_TABLE)->where('id = ?', $id)->fetch();
    }

    /**
     * @param iterable $data
     * @return bool|int|ActiveRow
     */
    public function createStyle(iterable $data) {
        return $this->explorer->table(self::DB_TABLE)->insert($data);
    }

    /**
     * @param int $id
     * @param iterable $data
     * @return int
     */
    public function editStyle(int $id, iterable $data): int {
        return $this->explorer->table(self::DB_TABLE)->where('id = ?', $id)->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteStyle(int $id): int {
        return $this->explorer->table(self::DB_TABLE)->where("id = ?", $id)->delete();
    }

    /**
     * @return Explorer
     */
    public function getExplorer(): Explorer
    {
        return $this->explorer;
    }
}
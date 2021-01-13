<?php

namespace App\Front\Styles;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\IRow;
use Nette\Http\Response;
use Nette\SmartObject;

/**
 * Class ButtonStyles
 * @package App\Front\Styles
 */
class ButtonStyles
{
    use SmartObject;

    const DB_TABLE = "button_styles";

    private Context $context;
    private Response $response;

    /**
     * ButtonStyles constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param array $styles
     * @return array
     */
    public static function getSelectBox(array $styles) {
        $arr = [];
        foreach ($styles as $style) $arr[$style->class] = $style->name;
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
     * @return array|IRow[]
     */
    public function getStyles(): array {
        return $this->context->table(self::DB_TABLE)->fetchAll();
    }

    /**
     * @param int $id
     * @return \Nette\Database\IRow|ActiveRow|null
     */
    public function getStyleById(int $id): ?ActiveRow {
        return $this->context->table(self::DB_TABLE)->where('id = ?', $id)->fetch();
    }

    /**
     * @param iterable $data
     * @return bool|int|ActiveRow
     */
    public function createStyle(iterable $data) {
        return $this->context->table(self::DB_TABLE)->insert($data);
    }

    /**
     * @param iterable $data
     * @return int
     */
    public function editStyle(iterable $data): int {
        return $this->context->table(self::DB_TABLE)->update($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteStyle(int $id): int {
        return $this->context->table(self::DB_TABLE)->where("id = ?", $id)->delete();
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}
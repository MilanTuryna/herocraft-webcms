<?php

namespace App\Front\Section;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;

/**
 * Class SectionManager
 * @package App\Front
 */
class SectionManager
{
    use SmartObject;

    const DB_TABLE = 'sections';

    private Context $context;

    /**
     * SectionManager constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param string $name
     * @param string $contentData
     * @param string $imageData
     * @param string $layoutData
     * @param string $buttonData
     * @param string $type
     * @return iterable
     */
    private function getIterableRow(string $name, string $contentData, string $imageData, string $layoutData, string $buttonData, string $type): iterable {
        return [
            "name" => $name,
            "contentData" => $contentData,
            "imageData" => $imageData,
            'layoutData' => $layoutData,
            'buttonData' => $buttonData,
            'type' => $type,
        ];
    }

    /**
     * @param $header
     * @param $subheading
     * @param $text
     * @return bool|int|ActiveRow
     */
    private function createHeader(?string $header, ?string $subheading, ?string $text) {
        return false;
    }

    /**
     * @return IRow|ActiveRow|null
     */
    public function getHeader() {
        return $this->context->table(self::DB_TABLE)->where('type = ?', Types::TYPE_HEADER)->fetch();
    }

    /**
     * @param string $heading
     * @param string $subheading
     * @param string $text
     * @return bool
     */
    public function setHeader(string $heading, string $subheading, string $text): bool {
        return false;
    }

    /**
     * @param int $id
     * @return array|\Nette\Database\Table\IRow[]
     */
    public function getDynamicSection(int $id): array {
        return $this->context->table(self::DB_TABLE)->where("type = ? AND id = ?", Types::TYPE_HEADER, $id)->fetchAll();
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}
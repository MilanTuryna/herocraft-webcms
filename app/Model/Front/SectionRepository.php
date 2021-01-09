<?php

namespace App\Front;

use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Elements\Card;
use App\Model\Front\UI\Elements\Image;
use App\Model\Front\UI\Elements\Text;
use App\Model\Front\UI\Parts\Section;
use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;

/**
 * Class SectionRepository
 * @package App\Front
 */
class SectionRepository
{
    const TABLE = 'sections';

    use SmartObject;

    private Context $context;

    /**
     * SectionRepository constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param string $name
     * @param string $content
     * @param string $anchor
     * @param string $backgroundColor
     * @param int $view
     * @param string|null $author
     * @return iterable
     */
    public static function getIterableRow(string $name, string $content, string $anchor, string $backgroundColor, int $view, ?string $author = null): iterable
    {
        $iterable = [
            "name" => $name,
            "content" => $content,
            "anchor" => $anchor,
            "bgColor" => $backgroundColor,
            "view" => $view
        ];
        if ($author) $iterable['author'] = $author;
        return $iterable;
    }

    /**
     * TODO: Test this method
     * Generating JSON with content data of section (all text, images and buttons)
     * @param Section $section
     * @return string
     */
    public static function generateJsonContent(Section $section): string {
        return json_encode([
                "text" => $section->text ? $section->text->toArray(): null,
                "image" => $section->image ? $section->image->toArray() : null,
                "button" => $section->button ? $section->button->toArray() : null,
                "card" => $section->card ? $section->card->toArray() : null,
            ]) ?: '{}';
    }

    /**
     * Returning Section object from ActiveRow $activeRow
     * @param ActiveRow $activeRow
     * @return Section|null
     */
    public static function parseSection(ActiveRow $activeRow): ?Section {
        $arrayRow = $activeRow->toArray();
        $content = json_decode($arrayRow['content'], true);
        $section = new Section($activeRow->name, new Text($content['text']['content'], $content['text']['color']), $activeRow->bgColor, $activeRow->view, $activeRow->anchor);
        $section->dbAuthor = $activeRow->author;
        $section->dbTime = $activeRow->time;
        $section->dbId = $activeRow->id;
        $sectionImage = $content['image'] ?? null;
        $sectionButton = $content['button'] ?? null;
        $sectionCard = $content['card'] ?? null;
        if($sectionImage) $section->image = new Image($sectionImage['url'], $sectionImage['align'], $sectionImage['width'], $sectionImage['height'], $sectionImage['alt']);
        if($sectionButton) $section->button = new Button(
            new Text($sectionButton['title']['content'], $sectionButton['title']['color']), $sectionButton['link']['url'],
            $sectionButton['link']['target'], $sectionButton['width'], $sectionButton['bgColor']);
        if($sectionCard) $section->card = new Card($sectionCard, new Text($sectionCard['text']['content'], $sectionCard['text']['color']), $sectionCard['align']);
        return $section;
    }

    /**
     * @param Section $section
     * @param string $author
     * @return bool|int|ActiveRow
     */
    public function createSection(Section $section, string $author = '') {
        return $this->context->table(SectionRepository::TABLE)->insert(
            self::getIterableRow($section->title,
                SectionRepository::generateJsonContent($section),
                $section->anchor,
                $section->bgColor,
                $section->section_view,
                $author)
        );
    }

    /**
     * @param ActiveRow[] $rows
     * @return Section[]
     */
    public static function rowsToSectionList(array $rows): array {
        $sectionList = [];
        foreach ($rows as $row) {
            if(!($row instanceof ActiveRow)) throw new \TypeError("Please, use ActiveRow[] array in rowsToSectionList method");
            array_push($sectionList, self::parseSection($row));
        }
        return $sectionList;
    }

    /**
     * @param int $id
     * @param Section $section
     * @return int
     */
    public function updateSection(int $id, Section $section): int {
        return $this->context->table(SectionRepository::TABLE)->where('id = ?', $id)->update(
            self::getIterableRow($section->title, SectionRepository::generateJsonContent($section), $section->anchor, $section->bgColor, $section->section_view)
        );
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteSection(int $id): int {
        return $this->context->table(SectionRepository::TABLE)->where('id = ?', $id)->delete();
    }

    /**
     * @param int $id
     * @return IRow|ActiveRow|null
     */
    public function getSectionById(int $id) {
        return $this->context->table(SectionRepository::TABLE)->where('id = ?', $id)->fetch();
    }

    /**
     * @return IRow[]
     */
    public function getAllSections(): array {
        return $this->context->table(SectionRepository::TABLE)->order('time DESC')->fetchAll();
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}
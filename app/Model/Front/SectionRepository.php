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
     * @param int|null $joinedSectionID
     * @param int|null $prioritySort
     * @return iterable
     */
    public static function getIterableRow(string $name, string $content, string $anchor, string $backgroundColor, int $view,
                                          ?string $author = null, ?int $joinedSectionID = null, ?int $prioritySort = null): iterable
    {
        $iterable = [
            "name" => $name,
            "content" => $content,
            "anchor" => $anchor,
            "bgColor" => $backgroundColor,
            "view" => $view
        ];
        if ($author) $iterable['author'] = $author;
        if ($joinedSectionID) $iterable['joinedSection_id'] = $joinedSectionID;
        if($prioritySort) $iterable['prioritySort'] = $prioritySort;
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
     * @param bool $recursion
     * @return Section|null
     */
    public function parseSection(ActiveRow $activeRow, bool $recursion = false): ?Section {
        $arrayRow = $activeRow->toArray();
        $content = json_decode($arrayRow['content'], true);
        $section = new Section($activeRow->name, new Text($content['text']['content'], $content['text']['color']), $activeRow->bgColor, $activeRow->view, $activeRow->anchor);
        if(!$recursion && $activeRow->joinedSection_id) $section->joinedSection = $this->parseSection($this->getSectionById($activeRow->joinedSection_id), true);
        $section->dbPrioritySort = $activeRow->prioritySort;
        $section->dbAuthor = $activeRow->author;
        $section->dbTime = $activeRow->time;
        $section->dbId = $activeRow->id;
        $sectionImage = $content['image'] ?? null;
        $sectionButton = $content['button'] ?? null;
        $sectionCard = $content['card'] ?? null;
        if($sectionImage) $section->image = new Image($sectionImage['url'], $sectionImage['align'], $sectionImage['width'], $sectionImage['height'], $sectionImage['alt']);
        if($sectionButton) $section->button = new Button(
            new Text($sectionButton['title']['content'], $sectionButton['title']['color']), $sectionButton['link']['url'], $sectionButton['style'],
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
                $author,
                $section->dbJoinedSectionID,
                $section->dbPrioritySort)
        );
    }

    /**
     * @param ActiveRow[] $rows
     * @return Section[]
     */
    public function rowsToSectionList(array $rows): array {
        $sectionList = [];
        foreach ($rows as $row) {
            if(!($row instanceof ActiveRow)) throw new \TypeError("Please, use ActiveRow[] array in rowsToSectionList method");
            array_push($sectionList, $this->parseSection($row));
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
            self::getIterableRow($section->title, SectionRepository::generateJsonContent($section), $section->anchor, $section->bgColor, $section->section_view,
                null, $section->dbJoinedSectionID, $section->dbPrioritySort)
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
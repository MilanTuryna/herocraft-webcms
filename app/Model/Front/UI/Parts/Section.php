<?php


namespace App\Model\Front\UI\Parts;

use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Elements\Card;
use App\Model\Front\UI\Elements\Text;
use App\Model\Front\UI\Elements\Image;
use Nette\SmartObject;

/**
 * Class Section
 * @package App\Model\Front\Object
 */
class Section
{
    use SmartObject;

    const DEFAULT_BACKGROUND_COLOR = "#ffffff";
    const DEFAULT_IMAGE_ALIGN = "left";

    public string $title;
    public string $bgColor = self::DEFAULT_BACKGROUND_COLOR;
    public int $section_view;

    public ?string $anchor;

    public Text $text;
    public ?Button $button = null;
    public ?Image $image = null;
    public ?Card $card = null;
    public ?Section $joinedSection = null;

    /** Non-required properties from database */
    public ?string $dbAuthor = null;
    public ?string $dbTime = null;
    public ?int $dbId = null;
    public ?int $dbJoinedSectionID = null;

    /**
     * Section constructor.
     * @param string $title
     * @param Text $text
     * @param string $bgColor
     * @param int $section_view
     * @param string|null $anchor
     */
    public function __construct(string $title,
                                Text $text,
                                string $bgColor,
                                int $section_view,
                                ?string $anchor)
    {
        $this->title = $title;
        $this->text = $text;
        $this->bgColor = $bgColor;
        $this->section_view = $section_view;
        $this->anchor = $anchor;
    }
}
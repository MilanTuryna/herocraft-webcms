<?php


namespace App\Model\Front\UI\Parts;

use App\Model\Front\UI\Elements\Button;
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

    private string $title;
    private string $bgColor = self::DEFAULT_BACKGROUND_COLOR;
    private string $imageAlign = "left";

    public Text $text;
    public ?Button $button;
    public ?Image $image;

    /**
     * Section constructor.
     * @param string $title
     * @param Text $text
     * @param string $bgColor
     */
    public function __construct(string $title, Text $text, string $bgColor)
    {
        $this->title = $title;
        $this->text = $text;
        $this->bgColor = $bgColor;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Text
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @param string $imageAlign
     */
    public function setImageAlign(string $imageAlign): void
    {
        $this->imageAlign = $imageAlign;
    }
}
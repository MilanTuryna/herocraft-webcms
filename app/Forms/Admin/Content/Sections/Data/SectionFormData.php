<?php

namespace App\Forms\Admin\Content\Sections\Data; 

use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Elements\Card;
use App\Model\Front\UI\Elements\Image;
use App\Model\Front\UI\Elements\Text;
use App\Model\Front\UI\Parts\Section;
use App\Model\Utils;

/**
 * Class SectionFormData
 * @package App\Forms\Admin\Content\Sections\Data
 */
class SectionFormData
{
    const DEFAULT_SECTION_VIEW = 1;
    const DEFAULT_PRIORITY_SORT = 1;
    const MAXIMAL_PRIORITY_SORT = 1000;
    const DEFAULT_IMAGE_WIDTH = "100%";
    const DEFAULT_IMAGE_HEIGHT = "auto";
    const DEFAULT_IMAGE_ALIGN = "left";
    const DEFAULT_BUTTON_WIDTH = Button::DEF_WIDTH;
    const DEFAULT_TEXT_Explorer = "## Ahoj, kamarade, vidim, ze jsi se rozhodl vytvorit novou webovou sekci, v tom ti nebranim, \njen te upozornuji, ze v tomto boxu, jsou zamerne zapnute HTML tagy, "
    . "tak si davej pozor co sem davas, \nprece jenom, nerad bys rozbil zbytek stranky, proto pokud nemas s HTML zkusenosti, tak tyto \ntagy nepouzivej a pis pouze cisty text!" .
    "\n\nTento text je automaticky vygenerovany, tudiz nez zacnes psat svuj obsah, vymaz ho!";
    const DEFAULT_CARD_ALIGN = "right";

    const SECTION_VIEWS = [
        1 => 'Zobrazit ihned po dokončení tvorby či editace',
        0 => 'Nezobrazovat a ponechat skryté připravené pro budoucí správu'
    ];

    const BUTTON_WIDTHS = [
        'auto' => 'Automaticky dle textu (výchozí)',
        '100%' => '100% (vyplnit prostor)',
    ];

    const IMAGE_WIDTHS = [
        '100%' => '100% (výchozí, přizpůsobit dle rozvržení)',
        '75%' => '75%',
        '50%' => '50%',
        'auto' => 'auto (původní velikost)'
    ];

    const IMAGE_HEIGHTS = [
        'auto' => 'Přizpůsobit dle šířky (výchozí)',
        '100%' => '100%',
        '75%' => '75%',
        '50%' => '50%',
    ];

    const BUTTON_TARGETS = [
        '_self' => 'Otevřít ve stejném okně (kartě)',
        '_blank' => 'Otevřít v novém okně'
    ];
    const ALIGNS = [
        'left' => 'Vlevo',
        'right' => 'Vpravo'
    ];

    public string $section_name;
    public string $section_backgroundColor;
    public ?string $section_anchor;
    public int $section_view; // boolean

    public string $text_content;
    public string $text_color;

    public ?string $image_url;
    public ?string $image_width;
    public ?string $image_height;
    public ?string $image_alt;
    public ?string $image_align;

    public ?string $button_text;
    public ?string $button_textColor;
    public ?string $button_link;
    public ?string $button_target;
    public ?string $button_width;
    public ?string $button_backgroundColor;
    public ?string $button_style;

    public ?string $card_title;
    public ?string $card_content;
    public ?string $card_align;

    public ?int $joinedSectionID;

    public int $prioritySort = self::DEFAULT_PRIORITY_SORT;

    /**
     * @return bool
     */
    public function isJoinedSectionID(): bool {
        return (bool)$this->joinedSectionID;
    }

    /**
     * Checking all $this->button_ parameters except button_style
     * @return bool
     */
    public function isImplementedButton(): bool {
        return $this->button_text  && $this->button_link && $this->button_textColor && $this->button_backgroundColor && $this->button_target;
    }

    /**
     * @return bool
     */
    public function isImplementedCard(): bool {
        return $this->card_title && $this->card_content && $this->card_align;
    }

    /**
     * @return bool
     */
    public function isImplementedImage(): bool {
        return $this->image_url  && $this->image_width && $this->image_height && $this->image_align;
    }

    /**
     * @param bool $condition
     * @return bool
     */
    public function isSameAligns(bool $condition = true): bool {
        if($condition && $this->card_align && $this->image_align) return $this->card_align === $this->image_align;
        return false;
    }

    /**
     * @return Section
     */
    public function getSection(): Section {
        $implementedButton = $this->isImplementedButton();
        $implementedImage = $this->isImplementedImage();
        $implementedCard = $this->isImplementedCard();

        $text = new Text($this->text_content, $this->text_color);
        $section = new Section($this->section_name, $text, $this->section_backgroundColor, $this->section_view, null);
        if($this->joinedSectionID) $section->dbJoinedSectionID = $this->joinedSectionID;
        $section->dbPrioritySort = $this->prioritySort;
        $section->anchor = $this->section_anchor ? Utils::parseURL($this->section_anchor) : Utils::parseURL($this->section_name);
        if($implementedImage) $section->image = new Image($this->image_url, $this->image_align, $this->image_width, $this->image_height, $this->image_alt);
        if($implementedButton) {
            $buttonText = new Text($this->button_text, $this->button_textColor);
            $section->button = new Button($buttonText,  $this->button_link, $this->button_style ?: '', $this->button_target, $this->button_width ?: Button::DEF_WIDTH, $this->button_backgroundColor);
        }
        if($implementedCard) $section->card = new Card($this->card_title, new Text($this->card_content, "#000000"), $this->card_align);
        if($this->isSameAligns($implementedCard && $implementedImage)) {
            $section->card->align = "right";
            $section->image->align = "left";
        }

        return $section;
    }
}
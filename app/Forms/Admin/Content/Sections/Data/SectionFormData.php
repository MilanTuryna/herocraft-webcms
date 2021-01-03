<?php

namespace App\Forms\Sections\Data;

use App\Model\Front\UI\Elements\Button;

/**
 * Class SectionFormData
 * @package App\Forms\Sections\Data
 */
class SectionFormData
{
    const DEFAULT_SECTION_VIEW = 1;
    const DEFAULT_IMAGE_WIDTH = "100%";
    const DEFAULT_IMAGE_HEIGHT = "auto";
    const DEFAULT_BUTTON_WIDTH = Button::DEF_WIDTH;

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
    const IMAGE_ALIGNS = [
        'left' => 'Vlevo',
        'right' => 'Vpravo'
    ];

    public string $section_name;
    public string $section_backgroundColor;
    public string $section_anchor;
    public int $section_view; // boolean

    public string $text_content;
    public string $text_color;

    public string $image_url;
    public string $image_width;
    public string $image_height;
    public string $image_alt;
    public string $image_align;

    public string $button_text;
    public string $button_textColor;
    public string $button_link;
    public string $button_target;
    public string $button_width;
    public string $button_backgroundColor;
}
<?php

namespace App\Forms\Content\Sections;

use App\Forms\Sections\Data\SectionFormData;
use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Elements\Image;
use App\Model\Front\UI\Elements\Text;
use App\Model\Front\UI\Parts\Section;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class CreateSectionForm
{
    private Presenter $presenter;

    /**
     * CreateSectionForm constructor.
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function create(): Form {
        $form = new Form;

        $form->addGroup('Základní nastavení');
        $form->addText('section_name', 'Název sekce/části')->setRequired(true);
        $form->addText('section_backgroundColor', 'Barva pozadí')
            ->setDefaultValue(Section::DEFAULT_BACKGROUND_COLOR)
            ->setRequired(true);
        $form->addText('section_anchor', 'Kotva')->setRequired(true);
        $form->addSelect('section_view', 'Zobrazení', SectionFormData::SECTION_VIEWS)
            ->setDefaultValue(SectionFormData::DEFAULT_SECTION_VIEW)->setRequired(true);

        $form->addGroup('Obsah');
        $form->addTextarea('text_content', 'Hlavní text')->setRequired(true);
        $form->addText('text_color', 'Barva textu')->setRequired(true);

        $form->addGroup('Obrázek');
        $form->addText('image_url', 'URL adresa obrázku')->setRequired(false)->addRule(Form::URL);
        $form->addSelect('image_width', 'Šířka obrázku', SectionFormData::IMAGE_WIDTHS)
            ->setDefaultValue(SectionFormData::DEFAULT_IMAGE_WIDTH)
            ->setRequired(false);
        $form->addSelect('image_height', 'Výška obrázku', SectionFormData::IMAGE_HEIGHTS)
            ->setDefaultValue(SectionFormData::DEFAULT_IMAGE_HEIGHT)
            ->setRequired(false);
        $form->addText('image_alt', 'Rekapitulace obrázku')->setRequired(false);
        $form->addSelect('image_align', '', SectionFormData::IMAGE_ALIGNS)->setRequired(false);

        $form->addGroup('Tlačítko');
        $form->addText('button_text', 'Obsah tlačítka')->setRequired(false);
        $form->addText('button_textColor', 'Barva textu')->setRequired(false);
        $form->addText('button_link', 'Odkaz tlačítka (URL)')->addRule(Form::URL)->setRequired(false);
        $form->addSelect('button_width', 'Šířka tlačítka', SectionFormData::BUTTON_WIDTHS)
            ->setDefaultValue(Button::DEF_WIDTH)
            ->setRequired(false);
        $form->addText('button_backgroundColor', 'Barva pozadí')->setDefaultValue(Button::DEF_BG_COLOR)->setRequired(false);
        $form->addSelect('button_target', 'Akce', SectionFormData::BUTTON_TARGETS)
            ->setPrompt('Zvolit akci tlačítka');

        $form->addSubmit('submit')->setRequired(true);

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param SectionFormData $data
     */
    public function success(Form $form, SectionFormData $data): void {
        // null ! empty atd..
        // checking if button or image is implemented, because it's non required - without width
        $implementedButton = $data->button_text
            && $data->button_link
            && $data->button_textColor
            && $data->button_backgroundColor
            && $data->button_target;
        // checking without alt because it's non required for a image
        $implementedImage = $data->image_url
            && $data->image_height
            && $data->image_align
            && $data->image_width;

        $text = new Text($data->text_content, $data->text_color);
        $section = new Section($data->section_name, $text, $data->section_backgroundColor);
        if($implementedImage) $section->image = new Image($data->image_url, $data->image_align, $data->image_width, $data->image_height, $data->image_alt);
        if($implementedButton) {
            $buttonText = new Text($data->button_text, $data->button_textColor);
            $section->button = new Button($buttonText, $data->button_link, $data->button_target, $data->button_width ?: Button::DEF_WIDTH, $data->button_backgroundColor);
        }
    }
}
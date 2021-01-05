<?php

namespace App\Forms\Content\Sections;

use App\Constants;
use App\Forms\Sections\Data\SectionFormData;
use App\Front\SectionRepository;
use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Elements\Card;
use App\Model\Front\UI\Elements\Image;
use App\Model\Front\UI\Elements\Text;
use App\Model\Front\UI\Parts\Section;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class CreateSectionForm
 * @package App\Forms\Content\Sections
 */
class CreateSectionForm
{
    private Presenter $presenter;
    private SectionRepository $sectionRepository;

    public string $author;
    public string $afterRedirect;

    /**
     * CreateSectionForm constructor.
     * @param Presenter $presenter
     * @param SectionRepository $sectionRepository
     * @param string $author
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, SectionRepository $sectionRepository, string $author = '', string $afterRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->sectionRepository = $sectionRepository;
        $this->author = $author;
        $this->afterRedirect = $afterRedirect;
    }

    public function create(): Form {
        $form = new Form;

        $form->addGroup('Základní nastavení');
        $form->addText('section_name', 'Název sekce/části')->setRequired(true);
        $form->addText('section_backgroundColor', 'Barva pozadí')
            ->setDefaultValue(Section::DEFAULT_BACKGROUND_COLOR)
            ->setRequired(true);
        $form->addText('section_anchor', 'Kotva')->setRequired(false);
        $form->addSelect('section_view', 'Zobrazení', SectionFormData::SECTION_VIEWS)
            ->setDefaultValue(SectionFormData::DEFAULT_SECTION_VIEW)->setRequired(true);

        $form->addGroup('Obsah');
        $form->addTextarea('text_content', 'Hlavní text')->setRequired(true)->setDefaultValue(SectionFormData::DEFAULT_TEXT_CONTEXT);
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
        $form->addSelect('image_align', 'Umístění obrázku', SectionFormData::ALIGNS)->setRequired(false);

        $form->addGroup('Tlačítko');
        $form->addText('button_text', 'Obsah tlačítka')->setRequired(false);
        $form->addText('button_textColor', 'Barva textu')->setRequired(false);
        $form->addText('button_link', 'Odkaz tlačítka (URL)')->addRule(Form::URL)->setRequired(false);
        $form->addSelect('button_width', 'Šířka tlačítka', SectionFormData::BUTTON_WIDTHS)
            ->setDefaultValue(SectionFormData::DEFAULT_BUTTON_WIDTH)
            ->setRequired(false);
        $form->addText('button_backgroundColor', 'Barva pozadí')->setDefaultValue(Button::DEF_BG_COLOR)->setRequired(false);
        $form->addSelect('button_target', 'Akce', SectionFormData::BUTTON_TARGETS)
            ->setPrompt('Zvolit akci tlačítka');

        $form->addGroup('Doplňková karta');
        $form->addText('card_title', 'Název karty')->setRequired(false);
        $form->addTextArea('card_content', 'Obsah karty')->setRequired(false);
        $form->addSelect('card_align', 'Umístění karty', SectionFormData::ALIGNS)->setDefaultValue(SectionFormData::DEFAULT_CARD_ALIGN)->setRequired(false);

        $form->addGroup('Odeslání');
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
     * @throws AbortException
     */
    public function success(Form $form, SectionFormData $data): void {
        $implementedButton = $data->button_text  && $data->button_link && $data->button_textColor && $data->button_backgroundColor && $data->button_target;
        $implementedImage = $data->image_url && $data->image_height && $data->image_align && $data->image_width;
        $implementedCard = $data->card_title && $data->card_content && $data->card_align;

        $text = new Text($data->text_content, $data->text_color);
        $section = new Section($data->section_name, $text, $data->section_backgroundColor, $data->section_view, null, $data->image_align);
        $section->anchor = $data->section_anchor ?: strtr($data->section_name, Constants::VALID_URL);
        if($implementedImage) $section->image = new Image($data->image_url, $data->image_align, $data->image_width, $data->image_height, $data->image_alt);
        if($implementedButton) {
            $buttonText = new Text($data->button_text, $data->button_textColor);
            $section->button = new Button($buttonText, $data->button_link, $data->button_target, $data->button_width ?: Button::DEF_WIDTH, $data->button_backgroundColor);
        }
        if($implementedCard) $section->card = new Card($data->card_title, new Text($data->card_content, "#000000"), $data->card_align);
        if($this->sectionRepository->createSection($section, $this->author)) {
            $this->presenter->flashMessage('Sekce s názvem "'.$data->section_name.'" byla úspěšně vytvořena!', 'success');
            if(!$implementedImage) $this->presenter->flashMessage('Sekce byla vytvořena bez připojených obrázků, pravděpodobně nebyly nastaveny.', 'info');
            if(!$implementedButton) $this->presenter->flashMessage('Sekce byla vytvořena bez připojených tlačítek, pravděpodobně nebyly nastaveny.', 'info');
        } else {
            $form->addError('Sekce nebyla vytvořena, nastala chyba při práci s databází!');
        }
        $this->presenter->redirect($this->afterRedirect);
    }
}
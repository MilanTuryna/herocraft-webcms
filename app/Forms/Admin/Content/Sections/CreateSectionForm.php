<?php

namespace App\Forms\Content\Sections;

use App\Forms\Sections\Data\SectionFormData;
use App\Front\SectionRepository;
use App\Front\Styles\ButtonStyles;
use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Parts\Section;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\Html;

/**
 * Class CreateSectionForm
 * @package App\Forms\Content\Sections
 */
class CreateSectionForm
{
    private Presenter $presenter;
    private SectionRepository $sectionRepository;
    private ButtonStyles $buttonStyles;

    public string $author;
    public string $afterRedirect;

    /**
     * CreateSectionForm constructor.
     * @param Presenter $presenter
     * @param SectionRepository $sectionRepository
     * @param ButtonStyles $buttonStyles
     * @param string $author
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, SectionRepository $sectionRepository, ButtonStyles $buttonStyles, string $author = '', string $afterRedirect = 'this')
    {
        $this->presenter = $presenter;
        $this->sectionRepository = $sectionRepository;
        $this->buttonStyles = $buttonStyles;
        $this->author = $author;
        $this->afterRedirect = $afterRedirect;
    }

    public function create(): Form {
        $form = new Form;
        $sections = $this->sectionRepository->getAllSections();
        $sectionSelectBox = [];
        foreach ($sections as $section) $sectionSelectBox[$section->id] = $section->name;

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
        $form->addRadioList('button_style', 'Styly tlačítka', $this->buttonStyles::getSelectBox($this->buttonStyles->getStyles()));
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

        $form->addGroup('Připojená');
        $form->addSelect('joinedSectionID', 'Připojená sekce', $sectionSelectBox)->setPrompt('Nepřipojeno')->setRequired(false);

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
        $section = $data->getSection();
        if($this->sectionRepository->createSection($section, $this->author)) {
            $this->presenter->flashMessage(Html::el()
                ->addText('Sekce s názvem ')
                ->addHtml(Html::el('strong')
                    ->setText($data->section_name))
                ->addText(' byla úspěšně vytvořena!'), 'success');
            $isCard = $data->isImplementedCard();
            $isImage = $data->isImplementedImage();
            if(!$isCard) $this->presenter->flashMessage('Sekce byla vytvořena bez připojených karet, pravděpodobně nebyly nastaveny.', 'info');
            if(!$data->isImplementedButton()) $this->presenter->flashMessage('Sekce byla vytvořena bez připojených tlačítek, pravděpodobně nebyly nastaveny.', 'info');
            if(!$isImage) $this->presenter->flashMessage("Sekce byla vytvořena bez připojených obrázků, pravděpodobně nebyly nastaveny.", 'info');
            if($data->isSameAligns(($isCard && $isImage))) $this->presenter->flashMessage("Bylo změněno umístění obrázku a karty, jelikož nemohou být na stejné straně.", "info");
        } else {
            $form->addError('Sekce nebyla vytvořena, nastala chyba při práci s databází!');
            $this->presenter->redirect("this");
        }
        $this->presenter->redirect($this->afterRedirect);
    }
}
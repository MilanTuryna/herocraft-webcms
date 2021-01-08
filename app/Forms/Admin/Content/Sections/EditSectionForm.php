<?php


namespace App\Forms\Content\Sections;

use App\Forms\Sections\Data\SectionFormData;
use App\Front\SectionRepository;
use App\Model\Front\UI\Elements\Button;
use App\Model\Front\UI\Parts\Section;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;

/**
 * Class EditSectionForm
 * @package App\Forms\Content\Sections
 */
class EditSectionForm
{
    use SmartObject;

    private Presenter $presenter;
    private SectionRepository $sectionRepository;
    private int $sectionId;
    private Section $parsedSection;

    public string $afterRedirect;

    /**
     * EditSectionForm constructor.
     * @param Presenter $presenter
     * @param SectionRepository $sectionRepository
     * @param int $sectionId
     * @param string $afterRedirect
     */
    public function __construct(Presenter $presenter, SectionRepository $sectionRepository, int $sectionId, string $afterRedirect = "this")
    {
        $this->presenter = $presenter;
        $this->sectionRepository = $sectionRepository;
        $this->sectionId = $sectionId;
        $this->afterRedirect = $afterRedirect;
        $this->parsedSection = $this->sectionRepository::parseSection($this->sectionRepository->getSectionById($sectionId));
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;

        $form->addGroup('Základní nastavení');
        $form->addText('section_name', 'Název sekce/části')->setDefaultValue($this->parsedSection->title)->setRequired(true);
        $form->addText('section_backgroundColor', 'Barva pozadí')
            ->setDefaultValue($this->parsedSection->bgColor)
            ->setRequired(true);
        $form->addText('section_anchor', 'Kotva')->setDefaultValue($this->parsedSection->anchor)->setRequired(false);
        $form->addSelect('section_view', 'Zobrazení', SectionFormData::SECTION_VIEWS)
            ->setDefaultValue($this->parsedSection->section_view)->setRequired(true);

        $form->addGroup('Obsah');
        $form->addTextarea('text_content', 'Hlavní text')->setRequired(true)->setDefaultValue($$this->parsedSection->text->content);
        $form->addText('text_color', 'Barva textu')->setRequired(true)->setDefaultValue($this->parsedSection->text->color);

        $form->addGroup('Obrázek');
        $form->addText('image_url', 'URL adresa obrázku')->setRequired(false)->addRule(Form::URL)
            ->setDefaultValue($this->parsedSection->image ? $this->parsedSection->image->url : '');
        $form->addSelect('image_width', 'Šířka obrázku', SectionFormData::IMAGE_WIDTHS)
            ->setDefaultValue($this->parsedSection->image ? $this->parsedSection->image->width : SectionFormData::DEFAULT_IMAGE_WIDTH)->setRequired(false);
        $form->addSelect('image_height', 'Výška obrázku', SectionFormData::IMAGE_HEIGHTS)
            ->setDefaultValue($this->parsedSection->image ? $this->parsedSection->image->height : SectionFormData::DEFAULT_IMAGE_HEIGHT)->setRequired(false);
        $form->addText('image_alt', 'Rekapitulace obrázku')
            ->setDefaultValue($this->parsedSection->image ? $this->parsedSection->image->alt : '')->setRequired(false);
        $form->addSelect('image_align', 'Umístění obrázku', SectionFormData::ALIGNS)
            ->setDefaultValue($this->parsedSection->image ? $this->parsedSection->image->align : '')->setRequired(false);

        $form->addGroup('Tlačítko');
        $form->addText('button_text', 'Obsah tlačítka')->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->title->content : '')->setRequired(false);
        $form->addText('button_textColor', 'Barva textu')->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->title->color : '')->setRequired(false);
        $form->addTextarea('button_css', 'Kaskádové styly (CSS)')->setDefaultValue($this->parsedSection->button && $this->parsedSection->button->css ?
            $this->parsedSection->button->css : '')
            ->setRequired(false);
        $form->addText('button_link', 'Odkaz tlačítka (URL)')
            ->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->link : '')->addRule(Form::URL)->setRequired(false);
        $form->addSelect('button_width', 'Šířka tlačítka', SectionFormData::BUTTON_WIDTHS)
            ->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->width : SectionFormData::DEFAULT_BUTTON_WIDTH)
            ->setRequired(false);
        $form->addText('button_backgroundColor', 'Barva pozadí')
            ->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->bgColor : Button::DEF_BG_COLOR)->setRequired(false);
        $form->addSelect('button_target', 'Akce', SectionFormData::BUTTON_TARGETS)
            ->setDefaultValue($this->parsedSection->button ? $this->parsedSection->button->target : '')
            ->setPrompt('Zvolit akci tlačítka');

        $form->addGroup('Doplňková karta');
        $form->addText('card_title', 'Název karty')->setDefaultValue($this->parsedSection->card ? $this->parsedSection->card->title : '')->setRequired(false);
        $form->addTextArea('card_content', 'Obsah karty')->setDefaultValue($this->parsedSection->card ? $this->parsedSection->card->text->content : '')->setRequired(false);
        $form->addSelect('card_align', 'Umístění karty', SectionFormData::ALIGNS)
            ->setDefaultValue($this->parsedSection->card ? $this->parsedSection->card->align : SectionFormData::DEFAULT_CARD_ALIGN)->setRequired(false);

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
    public function success(Form $form, SectionFormData $data) {
        $newSection = $data->getSection();
        $oldSection = $this->parsedSection;
        // nullable db properties for checking if old section is same as new section
        $oldSection->dbAuthor = null; $oldSection->dbId = null; $oldSection->dbTime = null;
        if($oldSection === $newSection) {
            $this->presenter->flashMessage("Nebyly zaznamenány žádné změny.", "info");
            $this->presenter->redirect($this->afterRedirect);
        }
        if($this->sectionRepository->updateSection($this->sectionId, $newSection)) {
            $this->presenter->flashMessage("Sekce s názvem " . $oldSection->title . " byla úspěšně změnena.", "success");
        } else {
            $form->addError("Změny byly zaznamenány, avšak proběhla neznámá chyba při práci s databází.", "danger");
        }
        $this->presenter->redirect($this->afterRedirect);
    }
}
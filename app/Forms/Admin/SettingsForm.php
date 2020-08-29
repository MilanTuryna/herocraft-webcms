<?php /** @noinspection ALL */

namespace App\Forms\Admin;


use App\Model\SettingsRepository;
use Nette\Application\UI\Presenter;
use Nette\Database\Context;
use Nette\Application\UI\Form;

/**
 * Class SettingsForm
 * @package App\Forms
 */
class SettingsForm
{
    private Presenter $presenter;
    private SettingsRepository $settings;

    /**
     * SettingsForm constructor.
     * @param Presenter $presenter
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Presenter $presenter, SettingsRepository $settingsRepository)
    {
        $this->presenter = $presenter;
        $this->settings = $settingsRepository;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $settings = $this->settings->getAllRows();

        $form = new Form;
        $form->addText('name', 'Název webu:')
            ->setDefaultValue($settings->nazev)
            ->setMaxLength(35)
            ->setRequired();
        $form->addText('ip', 'IP serveru:')
            ->setRequired()
            ->setMaxLength(35)
            ->setDefaultValue($settings->ip);
        $form->addSelect('udrzba', 'Údržba', [
            '0' => 'Neaktivní',
            '1' => 'Aktivní'
        ])->setDefaultValue($settings->udrzba)->setRequired();
        $form->addUpload('logo', 'Logo');
        $form->addSubmit('send', 'Odeslat');

        $form->onSuccess[] = [$this, 'success'];
        return $form;
    }

    public function success(Form $form, \stdClass $values) {
        $this->settings->setRows([
            "nazev" => $values->name,
            "ip" => $values->ip,
            "udrzba" => $values->udrzba,
        ]);
        if($values->logo->isOk() && $values->logo->isImage()) {
            if($this->settings->getLogo()) {
                $this->settings->deleteLogo();
            }
            $this->settings->setLogo($values->logo);
        } else {
            $this->presenter->flashMessage('Nastavení bylo úspěšně změněno, ale logo zůstalo původní.', 'info');
        }

        $this->presenter->flashMessage("Změna byla aplikována!", "success");
    }
}
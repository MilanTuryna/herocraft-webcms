<?php


namespace App\Forms\Admin\Social;

use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;
use Nette\Application\UI\Form;

use App\Model\DynamicRepository;

/**
 * Class EditForm
 * @package App\Forms\Admin\Social
 */
class EditForm
{
    Private Presenter $presenter;
    Private DynamicRepository $socialRepository;
    Private string $socialId;

    Private ActiveRow $social;

    /**
     * EditForm constructor.
     * @param Presenter $presenter
     * @param DynamicRepository $socialRepository
     * @param string $socialId
     */
    public function __construct(Presenter $presenter, DynamicRepository $socialRepository, string $socialId)
    {
        $this->presenter = $presenter;
        $this->socialRepository = $socialRepository;
        $this->socialId = $socialId;
    }

    public function create() {
        $form = new Form;
        $social = $this->socialRepository->findById($this->socialId);

        $form->addText('name', 'Název služby')
            ->setDefaultValue($social->name)
            ->setRequired()
            ->setMaxLength(30);
        $form->addText('link', 'URL sítě')
            ->setRequired()
            ->setDefaultValue($social->link)
            ->setMaxLength(128);
        $form->addText('color', 'Barva tlačítka')
            ->setMaxLength(6)
            ->setDefaultValue($social->color)
            ->setRequired();
        $form->addSubmit('submit')->setRequired();

        $this->social = $social;

        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        $duplicate = false;
        if($this->socialRepository->isDuplicated('name = ?', $values->name) && trim($values->name) !== trim($this->social->name)) {
            $duplicate = true;
        }

        if(!$duplicate) {
            $this->socialRepository->update('id = ?', $this->socialId, [
                'name' => $values->name,
                'link' => $values->link,
                'color' => $values->color
            ]);
            $this->presenter->flashMessage('Změny byly aktualizovány', 'success');
            $this->presenter->redirect('Social:edit', $this->socialId);
        } else {
            $form->addError('Tato sociální síť již byla přidána, zvolte prosím jiný název!');
        }
    }
}
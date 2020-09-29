<?php

namespace App\Forms\Minecraft;

use App\Model\API\Plugin\Events;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class EditEventRecordForm
 * @package App\Forms\Minecraft
 */
class EditEventRecordForm
{
    private Events $events;
    private Presenter $presenter;

    public function __construct(Events $events, Presenter $presenter)
    {
        $this->events = $events;
        $this->presenter = $presenter;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     */
    public function success(Form $form, \stdClass $values) {

    }
}
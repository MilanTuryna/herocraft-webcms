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

    private $eventId;

    /**
     * EditEventRecordForm constructor.
     * @param Events $events
     * @param Presenter $presenter
     * @param $eventId
     */
    public function __construct(Events $events, Presenter $presenter, $eventId)
    {
        $this->events = $events;
        $this->presenter = $presenter;
        $this->eventId = $eventId;
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
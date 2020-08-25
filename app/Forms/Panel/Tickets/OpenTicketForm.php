<?php


namespace App\Forms\Panel\Tickets;


use App\Model\Panel\Core\TicketRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class CloseTicketForm
 * @package App\Forms\Panel\Tickets
 */
class OpenTicketForm
{
    private TicketRepository $ticketRepository;
    private Presenter $presenter;
    private $ticketId;

    /**
     * CloseTicketForm constructor.
     * @param Presenter $presenter
     * @param TicketRepository $ticketRepository
     * @param $ticketId
     */
    public function __construct(Presenter $presenter, TicketRepository $ticketRepository, $ticketId)
    {
        $this->presenter = $presenter;
        $this->ticketRepository = $ticketRepository;
        $this->ticketId = $ticketId;
    }

    /**
     * @return Form
     */
    public function create(): Form {
        $form = new Form;
        $form->addSubmit('open')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'danger');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     */
    public function success(Form $form, \stdClass $values) {
        $this->ticketRepository->unlockTicket($this->ticketId);
        $this->presenter->flashMessage("Ticket <b>#" . $this->ticketId . "</b> byl úspěšně zpětně otevřen", 'success');
    }
}
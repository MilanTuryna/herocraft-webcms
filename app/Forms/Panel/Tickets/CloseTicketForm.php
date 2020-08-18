<?php


namespace App\Forms\Panel\Tickets;


use App\Model\Panel\Core\TicketRepository;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Class CloseTicketForm
 * @package App\Forms\Panel\Tickets
 */
class CloseTicketForm
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
        $form->addSubmit('close')->setRequired();
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     */
    public function success(Form $form, \stdClass $values) {
        $this->ticketRepository->lockTicket($this->ticketId);
        $this->presenter->flashMessage('Ticket byl úspěšně označen jako vyřešený.', 'dark-green');
    }
}
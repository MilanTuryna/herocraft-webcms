<?php


namespace App\Forms\Panel\Tickets;


use App\Model\Panel\Core\TicketRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

/**
 * Class AddTicketForm
 * @package App\Forms\Panel\Tickets
 */
class AddTicketForm
{
    private Presenter $presenter;
    private TicketRepository $ticketRepository;

    private ActiveRow $user;

    /**
     * AddTicketForm constructor.
     * @param Presenter $presenter
     * @param TicketRepository $ticketRepository
     * @param ActiveRow $user
     */
    public function __construct(Presenter $presenter, TicketRepository $ticketRepository, ActiveRow $user)
    {
        $this->presenter = $presenter;
        $this->ticketRepository = $ticketRepository;
        $this->user = $user;
    }

    public function create(): Form {
        $form = new Form;
        $form->addText('name', 'Název ticketu')->setRequired();
        $form->addTextArea('content', 'Obsah ticketu (první odpověď)')->setRequired();
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'success'];
        $form->onError[] = function() use ($form) {
            foreach($form->getErrors() as $error) $this->presenter->flashMessage($error, 'error');
        };
        return $form;
    }

    /**
     * @param Form $form
     * @param \stdClass $values
     * @throws AbortException
     */
    public function success(Form $form, \stdClass $values) {
        $row = $this->ticketRepository->addTicket([
            'name' => $values->name,
            'author' => $this->user->realname,
        ]);
        $this->ticketRepository->addResponse($row->id, [
            'author' => $this->user->realname,
            'type' => TicketRepository::TYPES['player'],
            'content' => $values->content,
        ]);

        $this->presenter->flashMessage('Ticket byl úspěšně vytvořen!', 'dark-green');
        $this->presenter->redirect('Ticket:view', $row->id);
    }
}
<?php

namespace App\Forms\Panel\Tickets;

use App\Model\Panel\Core\TicketRepository;
use App\Model\Panel\Object\Ticket;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\InvalidLinkException;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\JsonException;

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
        $form->addText('name', 'Název ticketu')
            ->addRule($form::MIN_LENGTH, "Název musí obsahovat aspoň 10 znaků", 10)
            ->addRule($form::MAX_LENGTH, 'Název nemůže být delší než 70 znaků', 70)
            ->setRequired();
        $form->addSelect('subject', 'Předmět ticketu', $this->ticketRepository->getSelectBox())
            ->setPrompt("Vyber předmět")
            ->setRequired();
        $form->addTextArea('content', 'Obsah ticketu (první odpověď)')
            ->addRule($form::MIN_LENGTH, "Obsah ticketu musí mít alespoň 100 znaků", 100)
            ->addRule($form::MAX_LENGTH, "Obsah ticketů nemůže mít více než 10000 znaků", 10000)
            ->setRequired();
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
            'subject' => $values->subject,
        ]);
        $this->ticketRepository->addResponse($row->id, [
            'author' => $this->user->realname,
            'type' => TicketRepository::TYPES['player'],
            'content' => $values->content,
        ]);
        $ticket = new Ticket($this->user->realname, $values->name, $values->subject, $values->content, $row->id);
        try {
            $this->ticketRepository->getTicketSettings()
                ->getDiscord()
                ->notify($ticket,
                    $this->presenter->link("//:Stats:Main:app?player=".$this->user->realname),
                    $this->presenter->link("//:HelpDesk:Main:ticket", $row->id));
        } catch (JsonException | InvalidLinkException $exception) {}

        $this->presenter->flashMessage('Ticket byl úspěšně vytvořen!', 'dark-green');
        $this->presenter->redirect('Ticket:view', $row->id);
    }
}
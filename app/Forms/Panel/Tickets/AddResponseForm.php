<?php

namespace App\Forms\Panel\Tickets;

use App\Model\Panel\Core\Tickets\TicketRepository;
use App\Model\Security\Form\Captcha;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

/**
 * Class AddResponseForm
 * @package App\Forms\Panel\Tickets
 */
class AddResponseForm
{
    private Presenter $presenter;
    private Captcha $captcha;
    private TicketRepository $ticketRepository;

    private ActiveRow $user;
    private $ticketId;
    private string $userType;

    /**
     * AddResponseForm constructor.
     * @param Presenter $presenter
     * @param Captcha $captcha
     * @param TicketRepository $ticketRepository
     * @param ActiveRow $user
     * @param $ticketid
     * @param string|null $userType
     */
    public function __construct(Presenter $presenter, Captcha $captcha, TicketRepository $ticketRepository, ActiveRow $user, $ticketid, string $userType = TicketRepository::TYPES['player'])
    {
        $this->presenter = $presenter;
        $this->captcha = $captcha;
        $this->ticketRepository = $ticketRepository;
        $this->user = $user;
        $this->ticketId = $ticketid;
        $this->userType = $userType;
    }

    public function create(): Form {
        $form = new Form;
        $form->addTextArea('content')->setRequired();
        $form->addText('captcha')->setRequired();
        $form->addSubmit('submit')->setRequired();
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
        if($this->captcha->verify($values->captcha)) {
            $this->ticketRepository->addResponse($this->ticketId, [
                'author' => $this->user->realname,
                'type' => $this->userType,
                'content' => $values->content
            ]);
            $this->presenter->flashMessage('Odpověď byla úspěšně odeslána.', 'dark-green');
        } else {
            $form->addError('Captcha byla neúspěšná, zkuste to prosím znovu!');
        }
    }
}
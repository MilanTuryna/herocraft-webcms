<?php

namespace App\Presenters\HelpDeskModule;

use App\Forms\Panel\Tickets\AddResponseForm;
use App\Forms\Panel\Tickets\CloseTicketForm;
use App\Forms\Panel\Tickets\OpenTicketForm;
use App\Model\Panel\Tickets\Email\Mails\NewResponseMail;
use App\Model\Panel\Tickets\TicketRepository;
use App\Model\Security\Auth\SupportAuthenticator;
use App\Model\Security\Exceptions\AuthException;
use App\Model\SettingsRepository;
use App\Presenters\HelpBasePresenter;
use Nette\Application\AbortException;
use App\Model\Security\Form\Captcha;
use Nette\Application\UI\Multiplier;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Paginator;

/**
 * Class MainPresenter
 * @package App\Presenters\HelpDeskModule
 */
class MainPresenter extends HelpBasePresenter
{
    private SupportAuthenticator $supportAuthenticator;
    private NewResponseMail $newResponseMail;
    private TicketRepository $ticketRepository;

    private ActiveRow $user;

    /**
     * MainPresenter constructor.
     * @param SupportAuthenticator $supportAuthenticator
     * @param NewResponseMail $newResponseMail
     * @param SettingsRepository $settingsRepository
     * @param TicketRepository $ticketRepository
     */
    public function __construct(SupportAuthenticator $supportAuthenticator, NewResponseMail $newResponseMail, SettingsRepository $settingsRepository, TicketRepository $ticketRepository)
    {
        parent::__construct($settingsRepository);

        $this->newResponseMail = $newResponseMail;
        $this->supportAuthenticator = $supportAuthenticator;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();
        $user = $this->supportAuthenticator->getUser();
        if($user) {
            $helper = new \stdClass();
            $helper->authUser = $user;

            $this->template->helper = $helper;
            $this->user = $user;
        } else {
            $this->flashMessage($this->translator->translate('helpdesk.flashMessages.pleaseAuthorize'));
            $this->redirect('Login:main');
        }
    }

    /**
     * @param int $page
     */
    public function renderHome(int $page = 1) {
        $ticketCounts = $this->ticketRepository->getAllTicketsCount();

        $this->template->ticketCount = $ticketCounts;

        $paginator = new Paginator();
        $paginator->setItemCount($ticketCounts); // celkový počet článků
        $paginator->setItemsPerPage(6); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        $tickets = $this->ticketRepository->getTickets($paginator->getLength(), $paginator->getOffset());

        $lastPage = $paginator->getLastPage();

        $this->template->tickets = $tickets;
        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) {
            $this->template->page = 0;
        }
    }

    /**
     * @param $id
     */
    public function renderTicket($id) {
        $ticket = $this->ticketRepository->getTicketById($id);
        $this->template->ticket = $ticket;
        $this->template->ticketResponses = $this->ticketRepository->getTicketResponses($id);
        $this->template->responseTypes = $this->ticketRepository::TYPES;
        $captcha = Captcha::getRandomMethod();
        $this->template->captcha = $captcha;
        $this->template->captchaOrder = Captcha::getMethodOrder($captcha);
    }

    /**
     * @param $ticketId
     * @throws AbortException
     */
    public function actionDeleteTicket($ticketId) {
        $deleted = $this->ticketRepository->removeTicket($ticketId);
        if($deleted) {
            $this->flashMessage($this->translator->translate('helpdesk.flashMessages.ticketSuccessDeleted', ['ticketId' => $ticketId]), "success");
        } else {
            $this->flashMessage($this->translator->translate('helpdesk.flashMessages.ticketErrorDeleted'), "danger");
        }

        $this->redirect("Main:home");
    }

    /**
     * @throws AbortException
     * @throws AuthException
     */
    public function actionLogout() {
        $this->supportAuthenticator->logout();
        $this->flashMessage($this->translator->translate('helpdesk.flashMessages.successLogout'), 'success');
        $this->redirect('Login:main');
    }

    /**
     * @return Multiplier
     */
    public function createComponentAddResponseForm(): Multiplier {
        return new Multiplier(function ($methodOrder) { // captcha
            return new Multiplier(function ($ticketId) use ($methodOrder) { // ticket
                return (new AddResponseForm($this, $this->newResponseMail,
                    new Captcha(array_keys(Captcha::methods)[$methodOrder]), $this->ticketRepository, $this->user, $ticketId, TicketRepository::TYPES['support']))
                    ->create();
            });
        });
    }

    /**
     * @return Multiplier
     */
    public function createComponentCloseTicketForm(): Multiplier {
        return new Multiplier(function ($ticketId) {
            return (new CloseTicketForm($this, $this->ticketRepository, $ticketId, $this->user->realname))->create();
        });
    }

    /**
     * @return Multiplier
     */
    public function createComponentOpenTicketForm(): Multiplier {
        return new Multiplier(function ($ticketId) {
            return (new OpenTicketForm($this, $this->ticketRepository, $ticketId))->create();
        });
    }
}
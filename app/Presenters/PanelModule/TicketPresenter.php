<?php


namespace App\Presenters\PanelModule;


use App\Forms\Panel\Tickets\AddResponseForm;
use App\Forms\Panel\Tickets\AddTicketForm;
use App\Forms\Panel\Tickets\CloseTicketForm;
use App\Model\DI\GoogleAnalytics;
use App\Model\Panel\Tickets\Email\Mails\NewResponseMail;
use App\Model\Panel\Tickets\Exceptions\TicketForbiddenException;
use App\Model\Panel\Tickets\Exceptions\TicketNotFoundException;
use App\Model\Panel\Tickets\TicketRepository;
use App\Model\Security\Form\Captcha;
use App\Model\Security\Auth\PluginAuthenticator;
use App\Model\SettingsRepository;
use App\Presenters\PanelBasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * Class TicketPresenter
 * @package App\Presenters\PanelModule
 */
class TicketPresenter extends PanelBasePresenter
{
    private NewResponseMail $newResponseMail;
    private TicketRepository $ticketRepository;
    private PluginAuthenticator $pluginAuthenticator;

    private ActiveRow $user;

    /**
     * TicketPresenter constructor.
     * @param SettingsRepository $settingsRepository
     * @param NewResponseMail $newResponseMail
     * @param PluginAuthenticator $pluginAuthenticator
     * @param TicketRepository $ticketRepository
     * @param GoogleAnalytics $googleAnalytics
     */
    public function __construct(SettingsRepository $settingsRepository, NewResponseMail $newResponseMail, PluginAuthenticator $pluginAuthenticator, TicketRepository $ticketRepository, GoogleAnalytics $googleAnalytics)
    {
        parent::__construct($settingsRepository, $googleAnalytics);

        $this->newResponseMail = $newResponseMail;
        $this->pluginAuthenticator = $pluginAuthenticator;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();
        $user = $this->pluginAuthenticator->getUser();
        if(!(bool)$user) {
            $this->flashMessage($this->translator->translate("panel.flashMessages.pleaseAuthorize"), 'error');
            $this->redirect('Login:ticketLogin', ['backLink' => $this->storeRequest()]);
        } else {
            $this->user = $user;
            $this->template->user = $user;
        }
    }

    /**
     * @param int $page
     */
    public function renderList(int $page = 1) {
        $tickets = $this->ticketRepository->getTicketsByAuthor($this->user->realname);

        $lastPage = 0;
        $ticketsPaginator = $tickets->page($page, 8, $lastPage);
        $this->template->tickets = $ticketsPaginator;
        $this->template->ticketsCount = $this->ticketRepository->getTicketsCount($this->user->realname);

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($lastPage === 0) {
            $this->template->page = 0;
        }
    }

    public function renderAdd() {
        try {
            $this->template->placeholderMessagesArray = Json::encode($this->ticketRepository
                ->getTicketSettings()
                ->getSubjects());
        } catch (JsonException $e) {
            $this->template->placeholderMessagesArray = "{}";
        }
    }

    /**
     * @param $id
     * @throws AbortException
     */
    public function renderView($id) {
        $ticket = $this->ticketRepository->getTicketById($id);

        try {
            if($ticket) {
                if($ticket->author === $this->user->realname) {
                    $this->template->ticket = $ticket;
                    $captcha = Captcha::getRandomMethod();
                    $this->template->captcha = $captcha;
                    $this->template->captchaOrder = Captcha::getMethodOrder($captcha);
                    $this->template->ticketResponses = $this->ticketRepository->getTicketResponses($ticket->id);
                    $this->template->responseTypes = TicketRepository::TYPES;
                } else {
                    throw new TicketForbiddenException('panel.flashMessages.forbiddenTicket');
                }
            } else {
                throw new TicketNotFoundException('panel.flashMessages.ticketNotExists');
            }
        } catch (TicketForbiddenException | TicketNotFoundException $exception) {
            $this->flashMessage($this->translator->translate($exception->getMessage()), 'error');
            $this->redirect('Ticket:list');
        }
    }

    /**
     * @return Multiplier
     */
    public function createComponentAddResponseForm(): Multiplier {
        return new Multiplier(function ($methodOrder) { // captcha
            return new Multiplier(function ($ticketId) use ($methodOrder) { // ticket
                return (new AddResponseForm($this, $this->newResponseMail, new Captcha(array_keys(Captcha::methods)[$methodOrder]),
                    $this->ticketRepository, $this->user, $ticketId))->create();
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
     * @return Form
     */
    public function createComponentAddTicketForm(): Form {
        return (new AddTicketForm($this, $this->ticketRepository, $this->user))->create();
    }
}
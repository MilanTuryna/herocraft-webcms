<?php

namespace App\Model\Panel\Tickets\Email\Mails;

use App\Model\DI\Tickets\EmailSender;
use App\Model\DI\Tickets\AbstractMail;
use App\Model\SettingsRepository;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\TemplateFactory;

/**
 * Class NewResponseMail
 * @package App\Model\Panel\Tickets\Mails
 */
class NewResponseMail extends AbstractMail
{
    /**
     * NewResponseMail constructor.
     * @param EmailSender $emailSender
     * @param LinkGenerator $linkGenerator
     * @param TemplateFactory $templateFactory
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(EmailSender $emailSender, LinkGenerator $linkGenerator, TemplateFactory $templateFactory, SettingsRepository $settingsRepository)
    {
        parent::__construct($emailSender, $linkGenerator, $templateFactory, $settingsRepository,"NewResponseMail");
    }

    /**
     * @param string $responseAuthor
     * @param string $responseContent
     * @param int $responseTimestamp
     * @param string $authorEmail
     * @param string $ticketId
     * @param string $ticketName
     * @return void
     */
    public function setEmail(string $responseAuthor, string $responseContent, int $responseTimestamp, string $authorEmail, string $ticketId, string $ticketName): void {
        $this->template->responseAuthor = $responseAuthor;
        $this->template->responseContent = $responseContent;
        $this->template->responseTime = $responseTimestamp;
        $this->template->ticketId = $ticketId;
        $this->template->ticketName = $ticketName;
        $this->message->addTo($authorEmail)->setSubject("Ticket #{$ticketId}: Nová odpověď od {$responseAuthor}");
    }
}
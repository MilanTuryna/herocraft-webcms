<?php


namespace App\Model\DI\Tickets;

use App\Model\Panel;
use App\Model\SettingsRepository;
use Nette;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\TemplateFactory;

/**
 * Class Mail
 * @package App\Model\DI\Tickets
 */
abstract class AbstractMail
{
    private EmailSender $emailSender;
    private TemplateFactory $templateFactory;
    private LinkGenerator $linkGenerator;
    private SettingsRepository $settingsRepository;

    private string $templateFile;

    /**
     * @var Nette\Application\UI\Template | Panel\Tickets\Email\TemplateType;
     */
    protected Nette\Application\UI\Template $template;
    protected Nette\Mail\Message $message;

    private function initMessage(): void {
        $this->message = $this->emailSender->getMessageBuild();
    }

    private function initTemplate(): void {
        $this->template = $this->templateFactory->createTemplate();
        $this->template->getLatte()->addProvider('uiControl', $this->linkGenerator);
    }

    /**
     * Mail constructor.
     * @param EmailSender $emailSender
     * @param LinkGenerator $linkGenerator
     * @param TemplateFactory $templateFactory
     * @param SettingsRepository $settingsRepository
     * @param string $mailName
     */
    public function __construct(EmailSender $emailSender, LinkGenerator $linkGenerator, TemplateFactory $templateFactory, SettingsRepository $settingsRepository, string $mailName)
    {
        $this->emailSender = $emailSender;
        $this->linkGenerator = $linkGenerator;
        $this->templateFactory = $templateFactory;
        $this->settingsRepository = $settingsRepository;
        $this->templateFile = __DIR__ . '/Latte/' . $mailName . '.latte';

        $this->initMessage();
        $this->initTemplate();
    }

    public function sendEmail(): void {
        $this->template->emailSubject = $this->message->getSubject();

        $settings = $this->settingsRepository->getAllRows();
        $this->template->webIp = $settings->ip;
        $this->template->webName = $settings->nazev;

        $this->template->emailSubject = $this->message->getSubject();

        $this->template->senderEmail = array_keys($this->message->getFrom())[0];
        $this->template->senderName = array_values($this->message->getFrom())[0];

        $this->message->setHtmlBody($this->template->renderToString($this->templateFile));
        $this->emailSender->getMailer()->send($this->message);
    }
}
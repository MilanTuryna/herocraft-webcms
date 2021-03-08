<?php


namespace App\Model\DI\Tickets;


use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

/**
 * Class EmailSender
 * @package App\Model\DI\Tickets
 */
class EmailSender
{
    const SECURE = 'ssl';

    private bool $enabled;
    private SmtpMailer $mailer;
    private Message $messageBuild;

    /**
     * EmailSender constructor.
     * @param bool $enabled
     * @param string $host
     * @param string $user
     * @param string $email
     * @param string $password
     */
    public function __construct(bool $enabled,
                                string $host,
                                string $user,
                                string $email,
                                string $password)
    {
        $this->enabled = $enabled;
        $this->mailer = new SmtpMailer([
            'host' => $host,
            'username' => $email,
            'password' => $password,
        ]);
        $this->messageBuild = (new Message())->setFrom($user);
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool {
        return $this->enabled;
    }

    /**
     * @return Message
     */
    public function getMessageBuild(): Message {
        return $this->messageBuild;
    }

    /**
     * @return SmtpMailer
     */
    public function getMailer(): SmtpMailer {
        return $this->mailer;
    }
}
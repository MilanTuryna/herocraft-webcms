<?php

namespace App\Model\DI\Tickets;

use Nette\SmartObject;
use App\Model\DI\Tickets\Callbacks\Discord;

/**
 * Class Settings
 * @package App\Model\DI\Tickets
 */
class Settings
{
    use SmartObject;

    private Discord $discord;
    private array $subjects;
    private EmailSender $emailSender;

    /**
     * Settings constructor.
     * @param array $subjects
     * @param Discord $discord
     * @param EmailSender $emailSender
     */
    public function __construct(array $subjects, Discord $discord, EmailSender $emailSender)
    {
        $this->subjects = $subjects;
        $this->discord = $discord;
        $this->emailSender = $emailSender;
    }

    /**
     * @return Discord
     */
    public function getDiscord(): Discord
    {
        return $this->discord;
    }

    /**
     * @return EmailSender
     */
    public function getEmailSender(): EmailSender
    {
        return $this->emailSender;
    }

    /**
     * @return array
     */
    public function getSubjects(): array
    {
        return $this->subjects;
    }
}
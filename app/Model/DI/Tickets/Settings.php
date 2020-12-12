<?php

namespace App\Model\DI\Tickets;

use Nette\SmartObject;
use App\Model\DI\Tickets\Callbacks\Discord;

class Settings
{
    use SmartObject;

    private Discord $discord;
    private array $subjects;
    private string $emailSender;

    /**
     * Settings constructor.
     * @param array $subjects
     * @param Discord $discord
     * @param string $emailSender
     */
    public function __construct(array $subjects, Discord $discord, string $emailSender)
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
     * @return array
     */
    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function getEmailSender(): string {
        return $this->emailSender;
    }
}
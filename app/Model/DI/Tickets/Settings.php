<?php

namespace App\Model\DI\Tickets\Callbacks;

use Nette\SmartObject;

class Settings
{
    use SmartObject;

    private Discord $discord;
    private array $subjects;

    /**
     * Settings constructor.
     * @param array $subjects
     * @param Discord $discord
     */
    public function __construct(array $subjects, Discord $discord)
    {
        $this->subjects = $subjects;
        $this->discord = $discord;
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
}
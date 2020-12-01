<?php

namespace App\Model\DI\Tickets\Callbacks;

use App\Model\Panel\Object\Ticket;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * Class Discord
 */
class Discord
{
    use SmartObject;

    const DEFAULT_EMBED_COLOR = "8508159"; // special discord color code

    private bool $enabled;
    private string $url;
    private string $color;
    private string $username;
    private ?string $logo;
    private LinkGenerator $linkGenerator;

    /**
     * Discord constructor.
     * @param bool $enabled
     * @param string $url
     * @param string $color
     * @param string|null $username
     * @param string|null $logo
     * @param LinkGenerator $linkGenerator
     */
    public function __construct(bool $enabled, ?string $url, ?string $color, ?string $username, ?string $logo, LinkGenerator $linkGenerator)
    {
        $this->enabled = $enabled;
        $this->url = $url ?: '';
        $this->color = $color ?: self::DEFAULT_EMBED_COLOR;
        $this->username = $username ?: "Tickety";
        $this->logo = $logo;
        $this->linkGenerator = $linkGenerator;
    }

    /**
     * @param Ticket $ticket
     * @param string $statsRoute
     * @param string $helpRoute
     * @return bool
     * @throws InvalidLinkException
     * @throws JsonException
     */
    public function notify(Ticket $ticket, string $statsRoute, string $helpRoute): bool {
        if(!$this->isEnabled()) return false;
        $response = Json::encode([
            "content" => null,
            "username" => $this->username,
            "avatar_url" => $this->logo,
            "tts" => false,
            "embeds" => [[
                "title" => "Nový ticket č. " . $ticket->getId(),
                "description" => "Hráč ".$ticket->getAuthor()." právě vytvořil nový ticket, nezapomeňte ho zkontrolovat!",
                "timestamp" => date("c", strtotime("now")),
                "color" => $this->color,
                "fields" => [
                    [
                        "name" => "Informace o ticketu",
                        "value" =>
                            "Autor: **[".$ticket->getAuthor()."](". $this->linkGenerator->link($statsRoute, [$ticket->getAuthor()]) .")**\n" .
                            "Název: **".substr($ticket->getName(), 0, 30)."**\n" .
                            "ID ticketu: **#" . $ticket->getId() . "**\n" .
                            "Zvolený předmět: **".$ticket->getSubject()."**\n" .
                            "Zobrazit ticket: **[ZDE](".$this->linkGenerator->link($helpRoute, [$ticket->getId()]).")**"
                    ],
                ],
                "footer" => [
                    "text" => "Notifikace z webového systému"
                ]
            ]]
        ]);
        $ch = curl_init($this->url);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $curlexec = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    /**
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Check if curl and this callback is enabled;
     * @return bool
     */
    public function isEnabled(): bool
    {
        return function_exists('curl_version') && $this->enabled;
    }
}
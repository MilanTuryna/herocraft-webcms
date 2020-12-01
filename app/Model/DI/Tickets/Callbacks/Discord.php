<?php

namespace App\Model\DI\Tickets\Callbacks;

use App\Model\Panel\Object\Ticket;
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

    /**
     * Discord constructor.
     * @param bool $enabled
     * @param string $url
     * @param string $color
     * @param string|null $username
     * @param string|null $logo
     */
    public function __construct(bool $enabled, ?string $url, ?string $color, ?string $username, ?string $logo)
    {
        $this->enabled = $enabled;
        $this->url = $url ?: '';
        $this->color = $color ?: self::DEFAULT_EMBED_COLOR;
        $this->username = $username ?: "Tickety";
        $this->logo = $logo;
    }

    /**
     * @param Ticket $ticket
     * @return bool
     */
    public function notify(Ticket $ticket): bool {
        if(!$this->isEnabled()) return false;
        try {
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
                                "Autor: **[".$ticket->getAuthor()."](https://petrzelkaasd.czewd)**\n" .
                                "Název: **".substr($ticket->getName(), 0, 30)."**\n" .
                                "ID ticketu: **#" . $ticket->getId() . "**\n" .
                                "Zvolený předmět: **".$ticket->getSubject()."**\n" .
                                "Zobrazit ticket: **[ZDE](https://petrzelkads.czads)**"
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
        } catch (JsonException $exception) {
            return false;
        }

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
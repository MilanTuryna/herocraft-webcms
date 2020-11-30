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

    const DEFAULT_EMBED_COLOR = "5814783"; // special discord color code

    private bool $enabled;
    private string $url;
    private string $color;

    /**
     * Discord constructor.
     * @param bool $enabled
     * @param string $url
     * @param string $color
     */
    public function __construct(bool $enabled, ?string $url, ?string $color)
    {
        $this->enabled = $enabled;
        $this->url = $url ?: '';
        $this->color = $color ?: self::DEFAULT_EMBED_COLOR;
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
                "tts" => false,
                "embeds" => [
                    "title" => "Nový ticket od hráče: " . $ticket->getAuthor(),
                    "color" => $this->color,
                    "fields" => [
                        [
                            "name" => "Informace o ticketu",
                            "value" =>
                                "Autor: **".$ticket->getAuthor()."**\n" .
                                "Název: **".$ticket->getName()."**\n" .
                                "Zvolený předmět: **".$ticket->getSubject()."**\n" .
                                "Obsah ticketu: **".substr($ticket->getContent(), 0, 60)."...**"
                        ],
                        [
                            "name" => "O autorovi",
                            "value" =>
                                "Herní nick: **" . $ticket->getAuthor() . "**\n" .
                                "Statistiky hráče: [kliknutím otevřít](https://das.cz)"
                        ],
                        [
                            "name" => "Odpovědět",
                            "value" => "Kliknutím na [odkaz](https://das.) budete přesunut do helpdesku, za předpokladu že jste přihlášen."
                        ]
                    ]
                ]
            ], Json::PRETTY);
            $ch = curl_init($this->url);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $response);
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        } catch (JsonException $exception) {
            return false;
        }

        return true;
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
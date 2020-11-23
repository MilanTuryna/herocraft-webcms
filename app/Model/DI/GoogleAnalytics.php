<?php

namespace App\Model\DI;

/**
 * Class GoogleAnalytics
 *
 * Class for getting configuration google analytics settings
 */
class GoogleAnalytics
{
    private string $code;
    private bool $enabled;

    /**
     * GoogleAnalytics constructor.
     * @param string $code
     * @param bool $enabled
     */
    public function __construct(string $code = "", bool $enabled = false)
    {
        $this->code = $code;
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public static function disabled() {
        return new self("", false);
    }
}
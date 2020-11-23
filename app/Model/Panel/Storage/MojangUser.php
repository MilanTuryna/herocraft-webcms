<?php

/**
 * Class MojangUser
 * Class represent mojang data of player in panel.
 */
class MojangUser
{
    private string $uuid;
    private string $skinURL;

    /**
     * @return string
     */
    public function getSkinURL(): string
    {
        return $this->skinURL;
    }

    /**
     * @return string
     */
    public function getUUID(): string {
        return $this->uuid;
    }
}
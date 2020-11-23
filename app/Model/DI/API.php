<?php


namespace App\Model\DI;

/**
 * Class API
 * @package App\Model\DI
 */
class API
{
    private string $expireTime;

    /**
     * API constructor.
     * @param string $expireTime
     */
    public function __construct(string $expireTime = "2 hours")
    {
        $this->expireTime = $expireTime;
    }

    /**
     * @return string
     */
    public function getExpireTime(): string
    {
        return $this->expireTime;
    }
}
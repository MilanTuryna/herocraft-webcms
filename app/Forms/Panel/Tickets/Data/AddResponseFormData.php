<?php

namespace App\Forms\Panel\Tickets\Data;

/**
 * Class AddResponseFormData
 * @package App\Forms\Panel\Tickets\Data
 */
class AddResponseFormData
{
    public string $content;
    public string $captcha;

    /**
     * @param int $offset
     * @param int $length
     * @return false|string
     */
    public function getCroppedContent(int $offset = 0, int $length = 60) {
        return substr($this->content, $offset, $length);
    }
}
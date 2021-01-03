<?php

namespace App\Front\UI;

/**
 * Interface IElement
 */
interface IElement
{
    /**
     * Returning element data in associative array
     * @return array
     */
    public function toArray(): array;
}
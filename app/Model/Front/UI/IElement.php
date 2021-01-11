<?php

namespace App\Front\UI;

/**
 * Interface IElement
 */
interface IElement
{
    /**
     * @return string
     */
    public function getElementName(): string;

    /**
     * Returning element data in associative array
     * @return array
     */
    public function toArray(): array;
}
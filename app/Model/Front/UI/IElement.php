<?php

namespace App\Model\Front\UI;

/**
 * Interface IElement
 */
interface IElement
{
    /**
     * @return string
     */
    public function toJSON(): string;

    /**
     * @return string
     */
    public function __toString(): string;
    public static function example(): IElement;
}


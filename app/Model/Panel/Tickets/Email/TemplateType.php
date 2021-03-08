<?php

namespace App\Model\Panel\Tickets\Email;

use Nette\SmartObject;

/**
 * Class Template
 * @package App\Model\Panel\Tickets
 */
class TemplateType
{
    use SmartObject;

    public string $emailSubject;
    public string $emailFrom;
    public string $webIp;
    public string $webName;
}
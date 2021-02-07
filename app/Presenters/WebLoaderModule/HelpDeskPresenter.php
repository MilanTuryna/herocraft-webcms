<?php


namespace App\Presenters\WebLoaderModule;

use App\Model\WebLoader\Modules\Specific\HelpDeskModule;
use App\Presenters\WebLoaderBasePresenter;

/**
 * Class HelpDeskPresenter
 * @package App\Presenters\WebLoaderModule
 */
class HelpDeskPresenter extends WebLoaderBasePresenter
{
    /**
     * HelpDeskPresenter constructor.
     * @param HelpDeskModule $module
     */
    public function __construct(HelpDeskModule $module)
    {
        parent::__construct($module);
    }
}
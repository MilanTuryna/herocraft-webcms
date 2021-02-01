<?php


namespace App\Presenters\WebLoaderModule;

use App\Model\WebLoader\Modules\Specific\AdminModule;
use App\Presenters\WebLoaderBasePresenter;

/**
 * Class AdminPresenter
 * @package App\Presenters\DynamicModule
 */
class AdminPresenter extends WebLoaderBasePresenter
{
    /**
     * AdminPresenter constructor.
     * @param AdminModule $module
     */
    public function __construct(AdminModule $module)
    {
        parent::__construct($module);
    }
}
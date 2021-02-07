<?php


namespace App\Presenters\WebLoaderModule;


use App\Model\WebLoader\Modules\Specific\PanelModule;
use App\Presenters\WebLoaderBasePresenter;

/**
 * Class PanelPresenter
 * @package App\Presenters\DynamicModule
 */
class PanelPresenter extends WebLoaderBasePresenter
{
    /**
     * PanelPresenter constructor.
     * @param PanelModule $module
     */
    public function __construct(PanelModule $module)
    {
        parent::__construct($module);
    }
}
<?php


namespace App\Presenters\WebLoaderModule;

use App\Model\WebLoader\Modules\Specific\StatsModule;
use App\Presenters\WebLoaderBasePresenter;

/**
 * Class StatsPresenter
 * @package App\Presenters\DynamicModule
 */
class StatsPresenter extends WebLoaderBasePresenter
{
    /**
     * StatsPresenter constructor.
     * @param StatsModule $module
     */
    public function __construct(StatsModule $module)
    {
        parent::__construct($module);
    }
}
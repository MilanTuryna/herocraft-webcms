<?php


namespace App\Presenters\WebLoaderModule;

use App\Model\WebLoader\Modules\Specific\FrontModule;
use App\Presenters\WebLoaderPresenter;

/**
 * Class FrontPresenter
 * @package App\Presenters\DynamicModule
 */
class FrontPresenter extends WebLoaderPresenter
{
    /**
     * FrontPresenter constructor.
     * @param FrontModule $frontModule
     */
    public function __construct(FrontModule $frontModule)
    {
        parent::__construct($frontModule);
    }
}
<?php


namespace App\Presenters;

use App\Model\DI;
use App\Model\Loader\Assets\Module;
use App\Model\Responses\CSSResponse;
use App\Model\Responses\JSResponse;
use App\WebLoader\Exceptions\ParseError;
use Nette\Application\AbortException;
use Nette\Application\Responses\TextResponse;

/**
 * Class WebLoaderPresenter
 * @package App\Presenters
 */
abstract class WebLoaderBasePresenter extends BasePresenter
{
    private Module $module;

    /**
     * WebLoaderPresenter constructor.
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        parent::__construct(DI\GoogleAnalytics::disabled());

        $this->module = $module;
    }

    /**
     * @throws AbortException
     */
    public function renderCSS() {
        try {
            $this->module->basePath = $this->getHttpRequest()->getUrl()->getHostUrl();
            $cssParser = $this->module->getParsedCSS();
            $parsedCSS = $cssParser->getComputedCode(true);
            $response = new CSSResponse($parsedCSS);
            $this->sendResponse($response);
        } catch (ParseError $e) {
            $textResponse = new TextResponse($e->getMessage());
            $this->sendResponse($textResponse);
        }

    }

    /**
     * @throws AbortException
     */
    public function renderJS() {
        $this->sendResponse(new JSResponse($this->module->getParsedJS()));
    }
}
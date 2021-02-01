<?php

namespace App\Presenters\DynamicModule;

use App\Front\Styles\ButtonStyles;
use App\Model\Responses\CSSResponse;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;

/**
 * Class CSSPresenter
 * @package App\Presenters\DynamicModule
 */
class CSSPresenter extends Presenter
{
    use SmartObject;

    private ButtonStyles $buttonStyles;

    /**
     * CSSPresenter constructor.
     * @param ButtonStyles $buttonStyles
     */
    public function __construct(ButtonStyles $buttonStyles)
    {
        parent::__construct();

        $this->buttonStyles = $buttonStyles;
    }

    /**
     * @throws AbortException
     */
    public function actionButtons() {
        $responseContent = "/* Dynamically created file from intern database */ \n";
        $responseContent .= "button:focus {box-shadow: none!important;}\n"; // resetting default bootstrap blue-shadow
        $responseContent .= "a.btn:focus, a.btn:active {box-shadow: none!important;}\n";
        foreach ($this->buttonStyles->getStyles() as $style) {
            $responseContent .= "\n/* " . $style->class . " -> " . $style->name . " */\n";
            $responseContent .= "" . $style->css;
      }
        $this->sendResponse(new CSSResponse($responseContent));
    }
}
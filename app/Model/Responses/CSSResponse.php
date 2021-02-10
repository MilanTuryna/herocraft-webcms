<?php


namespace App\Model\Responses;

use Nette;

/**
 * Class CSSResponse
 * @package App\Model\Responses
 */
class CSSResponse implements \Nette\Application\Response
{
    use Nette\SmartObject;

    private string $content;
    private string $type;
    private string $charset;

    /**
     * CSSResponse constructor.
     * @param string $content
     * @param string $charset
     * @param string $type
     */
    public function __construct(string $content, string $charset = 'utf-8', string $type = 'text/css')
    {
        $this->content = $content;
        $this->charset = $charset;
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse): void
    {
        $httpResponse->setContentType($this->type, $this->charset);
        echo trim($this->content);
    }
}
<?php


namespace App\Model\Responses;


use Nette;
use Nette\Application\Response;

/**
 * Class JSResponse
 * @package App\Model\Responses
 */
class JSResponse implements Response
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
    public function __construct(string $content, string $charset = 'utf-8', string $type = 'text/javascript')
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
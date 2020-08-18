<?php

namespace App\Model\Responses;

use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Application\IResponse as AppResponse;

/**
 * Class Json
 *
 * Alternative for JsonResponse.php in Nette SRC for pretty-print
 */
class PrettyJsonResponse implements AppResponse
{
    use SmartObject;

    /** @var mixed */
    private $payload;

    /** @var string */
    private string $contentType;


    public function __construct($payload, string $contentType = null)
    {
        $this->payload = $payload;
        $this->contentType = $contentType ?: 'application/json';
    }

    /**
     * Returns the MIME content type of a downloaded file.
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param IRequest $httpRequest
     * @param IResponse $httpResponse
     * @throws JsonException
     */
    function send(IRequest $httpRequest, IResponse $httpResponse): void
    {
        // TODO: Implement send() method.
        $httpResponse->setContentType($this->contentType, 'utf-8');
        echo Json::encode($this->payload, Json::PRETTY);
    }
}
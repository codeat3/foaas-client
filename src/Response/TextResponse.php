<?php
namespace Codeat3\FoaasClient\Response;

class TextResponse implements FoaasResponse
{
    /**
     * A header string for the request
     *
     * @var string
     */
    protected $acceptHeader = 'text/plain';

    /**
     * Returns the header type
     *
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->acceptHeader;
    }

    /**
     * Returns a response
     *
     * @param string $response
     *
     * @return string
     */
    public function response(string $response): string
    {
        return $response;
    }
}

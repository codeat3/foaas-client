<?php

namespace Codeat3\FoaasClient\Response;

use Codeat3\FoaasClient\ResponseFilters\FoaasFilter;

class HtmlResponse implements FoaasResponse
{
    /**
     * A header string for the request.
     *
     * @var string
     */
    protected $acceptHeader = 'text/html';

    /**
     * Returns the header type.
     *
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->acceptHeader;
    }

    /**
     * Returns a response.
     *
     * @param string $response
     *
     * @return string
     */
    public function response(string $response, FoaasFilter $filter)
    {
        return $filter->filter($response);
    }
}

<?php

namespace Codeat3\FoaasClient\Response;

use Codeat3\FoaasClient\ResponseFilters\FoaasFilter;

interface FoaasResponse
{
    /**
     * Returns the header type.
     *
     * @return string
     */
    public function getHeaders(): string;

    /**
     * Returns a response.
     *
     * @param string $response
     *
     * @return string
     */
    public function response(string $response, FoaasFilter $filter);
}

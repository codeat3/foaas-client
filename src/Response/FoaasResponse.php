<?php
namespace Codeat3\FoaasClient\Response;

interface FoaasResponse
{
    /**
     * Returns the header type
     *
     * @return string
     */
    public function getHeaders(): string;

    /**
     * Returns a response
     *
     * @param string $response
     *
     * @return string
     */
    public function response(string $response): string;
}

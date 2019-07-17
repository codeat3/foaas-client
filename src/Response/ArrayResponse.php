<?php
namespace Codeat3\FoaasClient\Response;

use Codeat3\FoaasClient\ResponseFilters\FoaasFilter;

class ArrayResponse extends JsonResponse
{

    /**
     * Returns a response
     *
     * @param string $response
     *
     * @return string
     */
    public function response(string $response, FoaasFilter $filter)
    {
        return json_decode($filter->filter($response), true);
    }
}

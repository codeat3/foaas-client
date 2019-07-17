<?php
namespace Codeat3\FoaasClient\ResponseFilters;

class NoFilter implements FoaasFilter
{
    public function filter($response)
    {
        return $response;
    }
}

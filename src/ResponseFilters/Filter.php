<?php
namespace Codeat3\FoaasClient\ResponseFilters;

use Codeat3\FoaasClient\ResponseFilters\FoaasFilter;

class Filter implements FoaasFilter
{
    protected $decencyLevelMap = [
        'low' => [
            'search' => 'fuck',
            'replace'=> 'f*ck',
        ],
        'medium' => [
            'search' => 'fuck',
            'replace'=> 'f*#k',
        ],
        'high' => [
            'search' => 'fuck',
            'replace'=> 'f*#$',
        ],
        'extreme' => [
            'search' => 'fuck',
            'replace'=> '!*#$',
        ]
    ];

    protected $decencyLevel;

    public function __construct($decency)
    {
        $this->decencyLevel = $decency;
    }

    public function filter($response)
    {
        return str_ireplace($this->decencyLevelMap[$this->decencyLevel]['search'], $this->decencyLevelMap[$this->decencyLevel]['replace'], $response);
    }
}

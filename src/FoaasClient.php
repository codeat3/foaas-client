<?php

namespace Codeat3\FoaasClient;

use GuzzleHttp\Client;
use Codeat3\FoaasClient\Exceptions\InvalidArgumentsException;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCallException;

class FoaasClient
{
    const API_ENDPOINT = 'https://foaas.com';
    protected static $endpoints;
    protected static $guzzleClient;

    protected $method;
    protected $arguments;

    protected $typeHeaderMap = [
        'json' => 'application/json',
        'nocache' => 'application/json',
        'text' => 'text/plain',
        'html' => 'text/html',
        'xml' => 'application/xml',

    ];

    public function guzzleClient($responseType = 'json')
    {
        if ($responseType == 'nocache') {
            return new Client([
                'headers' => [
                    'Accept' => $this->typeHeaderMap[$responseType]
                ],
            ]);
        }
        if (is_null(self::$guzzleClient)) {
            self::$guzzleClient = new Client([
                'headers' => [
                    'Accept' => $this->typeHeaderMap[$responseType]
                ],
            ]);
        }
        return self::$guzzleClient;
    }

    private function buildUrl($method, array $fields = []) : string
    {
        $url = '/' . $method;
        if (count($fields) > 0) {
            $url .= '/' . implode('/', $fields);
        }
        return self::API_ENDPOINT . $url;
    }

    public function __call($method, $arguments)
    {
        // check if the method is supported
        if (array_key_exists($method, $this->getAvailableEndpoints())) {
            $argumentsExpected = $this->getAvailableEndpoints()[$method]['fields'];
            if (count($arguments) === count($argumentsExpected)) {
                $this->url = $this->buildUrl($method, $arguments);
            } else {
                throw new InvalidArgumentsException();
            }
        } else {
            throw new InvalidMethodCallException();
        }
        return $this;
    }

    public function getAvailableEndpoints() : array
    {
        if (is_null(self::$endpoints)) {
            $response = json_decode($this->apiCall($this->buildUrl('operations'), 'nocache'), true);
            foreach ($response as $operation) {
                $arrUrl = array_filter(explode("/", $operation['url']));
                $endpoint = $arrUrl[1];
                $fields = array_slice($arrUrl, 1);
                self::$endpoints[$endpoint] = [
                    'fields' => $fields,
                    'name' => $operation['name'],
                ];
            }
        }
        return self::$endpoints;
    }

    public function apiCall($path, $responseType = 'json')
    {
        $response = $this->guzzleClient($responseType)->get($path);
        return $response->getBody()->getContents();
    }

    public function asArray()
    {
        return json_decode($this->apiCall($this->url, 'json'), true);
    }

    public function asJson()
    {
        return $this->apiCall($this->url, 'json');
    }

    public function asText()
    {
        return $this->apiCall($this->url, 'text');
    }

    public function asHtml()
    {
        return $this->apiCall($this->url, 'html');
    }
}

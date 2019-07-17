<?php

namespace Codeat3\FoaasClient;

use GuzzleHttp\Client;
use Codeat3\FoaasClient\Response\XmlResponse;
use Codeat3\FoaasClient\Response\HtmlResponse;
use Codeat3\FoaasClient\Response\JsonResponse;
use Codeat3\FoaasClient\Response\TextResponse;
use Codeat3\FoaasClient\Response\ArrayResponse;
use Codeat3\FoaasClient\ResponseFilters\Filter;
use Codeat3\FoaasClient\ResponseFilters\NoFilter;
use Codeat3\FoaasClient\Exceptions\InvalidArguments;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCall;

class FoaasClient
{
    protected static $endpoints;

    protected $responseType;
    protected static $responseAs;

    protected $url;

    protected $responseTypeMap = [
        'xml' => XmlResponse::class,
        'json' => JsonResponse::class,
        'text' => TextResponse::class,
        'html' => HtmlResponse::class,
        'array' => ArrayResponse::class,
    ];

    protected $method;
    protected $arguments;
    protected $decencyLevel;

    public function __construct(array $config = [])
    {
        $this->decencyLevel = $config['decency'] ?? null;
        $this->responseType = $config['responseAs'] ?? null;

        $this->responseTypeMap = array_merge($this->responseTypeMap, ResponseFormatValidator::validate($config['responseFormats'] ?? []));
    }

    private function getResponseType()
    {
        if (is_null(self::$responseAs)) {
            if (array_key_exists($this->responseType, $this->responseTypeMap)) {
                self::$responseAs = new $this->responseTypeMap[$this->responseType]();
            } else {
                return new $this->responseTypeMap['json']();
            }
        }
        return self::$responseAs;
    }

    public function guzzleClient()
    {
        $headers = $this->getResponseType()->getHeaders();
        return new Client([
            'headers' => [
                'Accept' => $headers
            ],
        ]);
    }

    public function __call($method, $arguments)
    {
        // check if the method is supported
        if (array_key_exists($method, $this->getAvailableEndpoints())) {
            $argumentsExpected = $this->getAvailableEndpoints()[$method]['fields'];
            if (count($arguments) === count($argumentsExpected)) {
                $this->url = UrlBuilder::buildUrl($method, $arguments);
            } else {
                throw new InvalidArguments();
            }
        } else {
            throw new InvalidMethodCall();
        }
        return $this;
    }

    public function getAvailableEndpoints() : array
    {
        if (is_null(self::$endpoints)) {
            $response = json_decode($this->apiCall(UrlBuilder::buildUrl('operations')), true);
            self::$responseAs = null;
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

    public function apiCall($path)
    {
        // echo $path . PHP_EOL;
        $response = $this->guzzleClient()->get($path);
        return $response->getBody()->getContents();
    }

    /**
     * A method to get the response
     *
     * @return string
     */
    public function get()
    {
        $apiResponse = $this->apiCall($this->url);

        $filter = (empty($this->decencyLevel))
            ? new NoFilter()
            : new Filter($this->decencyLevel);

        return $this->getResponseType()->response($apiResponse, $filter);
    }

    private function resetResponseType($type)
    {
        self::$responseAs = null;
        $this->responseType = $type;
    }

    public function getAsJson(): string
    {
        $this->resetResponseType('json');
        return $this->get();
    }

    public function getAsXml(): string
    {
        $this->resetResponseType('xml');
        return $this->get();
    }

    public function getAsArray(): array
    {
        $this->resetResponseType('array');
        return $this->get();
    }

    public function getAsText():string
    {
        $this->resetResponseType('text');
        return $this->get();
    }

    public function getAsHtml():string
    {
        $this->resetResponseType('html');
        return $this->get();
    }
}

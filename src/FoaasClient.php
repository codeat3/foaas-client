<?php

namespace Codeat3\FoaasClient;

use GuzzleHttp\Client;
use Codeat3\FoaasClient\Response\XmlResponse;
use Codeat3\FoaasClient\Response\HtmlResponse;
use Codeat3\FoaasClient\Response\JsonResponse;
use Codeat3\FoaasClient\Response\TextResponse;
use Codeat3\FoaasClient\Exceptions\InvalidArguments;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCall;

class FoaasClient
{
    protected static $endpoints;
    protected static $guzzleClient;

    protected $responseType;
    protected static $responseAs;

    protected $url;

    protected $responseTypeMap = [
        'xml' => XmlResponse::class,
        'json' => JsonResponse::class,
        'text' => TextResponse::class,
        'html' => HtmlResponse::class,
    ];

    protected $decencyLevelMap = [
        'nil' => [
            'search' => '',
            'replace' => '',
        ],
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

    protected $method;
    protected $arguments;
    protected $decencyLevel;

    public function __construct(array $config = [])
    {
        $this->decencyLevel = $config['decency'] ?? null;
        $this->responseType = $config['responseAs'] ?? null;

        $this->responseTypeMap = array_merge($this->responseTypeMap, ResponseFormatValidator::validate($config['responseFormats'] ?? null));
    }

    private function getResponseType()
    {
        if (is_null(self::$responseAs)) {
            if (array_key_exists($this->responseType, $this->responseTypeMap)) {
                self::$responseAs = new $this->responseTypeMap[$this->responseType]();
            }
            return new $this->responseTypeMap['json']();
        }
        return self::$responseAs;
    }

    public function guzzleClient()
    {
        return new Client([
            'headers' => [
                'Accept' => $this->getResponseType()->getHeaders()
            ],
        ]);
    }

    public function __call($method, $arguments)
    {
        // check if the method is supported
        if (array_key_exists($method, $this->getAvailableEndpoints())) {
            $argumentsExpected = $this->getAvailableEndpoints()[$method]['fields'];
            self::$guzzleClient = null;
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
        $response = $this->guzzleClient()->get($path);
        return $response->getBody()->getContents();
    }

    /**
     * A method to get the response
     *
     * @return string
     */
    public function get():string
    {
        $apiResponse = $this->apiCall($this->url);
        return $this->getResponseType()->response($apiResponse);
    }

    /**
     * An alias to get the response as json string
     *
     * @return string
     */
    public function getAsJson():string
    {
        $this->responseType = 'json';
        return $this->get();
    }

    /**
     * An alias to get the response as XML string
     *
     * @return string
     */
    public function getAsXml():string
    {
        $this->responseType = 'xml';
        return $this->get();
    }
}

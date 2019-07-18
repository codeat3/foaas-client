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

/**
 * @method anyway(string $company, string $from)
 * @method asshole(string $from)
 * @method awesome(string $from)
 * @method back(string $name, string $from)
 * @method bag(string $from)
 * @method ballmer(string $name, string $company, string $from)
 * @method bday(string $name, string $from)
 * @method because(string $from)
 * @method blackadder(string $name, string $from)
 * @method bm(string $name, string $from)
 * @method bucket(string $from)
 * @method bus(string $name, string $from)
 * @method bye(string $from)
 * @method caniuse(string $tool, string $from)
 * @method chainsaw(string $name, string $from)
 * @method cocksplat(string $name, string $from)
 * @method cool(string $from)
 * @method cup(string $from)
 * @method dalton(string $name, string $from)
 * @method deraadt(string $name, string $from)
 * @method diabetes(string $from)
 * @method donut(string $name, string $from)
 * @method dosomething(string $do, string $something, string $from)
 * @method equity(string $name, string $from)
 * @method everyone(string $from)
 * @method everything(string $from)
 * @method family(string $from)
 * @method fascinating(string $from)
 * @method field(string $name, string $from, string $reference)
 * @method flying(string $from)
 * @method ftfy(string $from)
 * @method fts(string $name, string $from)
 * @method fyyff(string $from)
 * @method gfy(string $name, string $from)
 * @method give(string $from)
 * @method greed(string $noun, string $from)
 * @method horse(string $from)
 * @method immensity(string $from)
 * @method ing(string $name, string $from)
 * @method jinglebells(string $from)
 * @method keep(string $name, string $from)
 * @method keepcalm(string $reaction, string $from)
 * @method king(string $name, string $from)
 * @method life(string $from)
 * @method linus(string $name, string $from)
 * @method logs(string $from)
 * @method look(string $name, string $from)
 * @method looking(string $from)
 * @method madison(string $name, string $from)
 * @method maybe(string $from)
 * @method me(string $from)
 * @method mornin(string $from)
 * @method no(string $from)
 * @method nugget(string $name, string $from)
 * @method off(string $name, string $from)
 * @method off-with(string $behavior, string $from)
 * @method outside(string $name, string $from)
 * @method particular(string $thing, string $from)
 * @method pink(string $from)
 * @method problem(string $name, string $from)
 * @method programmer(string $from)
 * @method pulp(string $language, string $from)
 * @method question(string $from)
 * @method ratsarse(string $from)
 * @method retard(string $from)
 * @method ridiculous(string $from)
 * @method rtfm(string $from)
 * @method sake(string $from)
 * @method shakespeare(string $name, string $from)
 * @method shit(string $from)
 * @method shutup(string $name, string $from)
 * @method single(string $from)
 * @method thanks(string $from)
 * @method that(string $from)
 * @method think(string $name, string $from)
 * @method thinking(string $name, string $from)
 * @method this(string $from)
 * @method thumbs(string $name, string $from)
 * @method too(string $from)
 * @method tucker(string $from)
 * @method version(string )
 * @method waste(string $name, string $from)
 * @method what(string $from)
 * @method xmas(string $name, string $from)
 * @method yoda(string $name, string $from)
 * @method you(string $name, string $from)
 * @method zayn(string $from)
 * @method zero(string $from)
 */
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
                'Accept' => $headers,
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
                $arrUrl = array_filter(explode('/', $operation['url']));
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
     * A method to get the response.
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

<?php
use Codeat3\FoaasClient\FoaasClient;
use Codeat3\FoaasClient\Response\FoaasResponse;

require_once "vendor/autoload.php";

class JsonResponse implements FoaasResponse
{
    protected $acceptHeader = 'text/html';

    public function getHeaders():string
    {
        return $this->acceptHeader;
    }

    public function response(string $response):string
    {
        return $response;
    }
}


$foaasClient = new FoaasClient([
    'responseFormats' => [
        'json' => JsonResponse::class,
    ]
]);
echo $foaasClient->what('John')->get();

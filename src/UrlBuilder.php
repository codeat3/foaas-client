<?php
namespace Codeat3\FoaasClient;

class UrlBuilder
{
    private const API_ENDPOINT = 'https://foaas.com';

    /**
     * Build Api Url
     *
     * @param string $method
     * @param array $fields
     *
     * @return string
     */
    public static function buildUrl(string $method, array $fields = []): string
    {
        $url = '/' . $method;
        if (count($fields) > 0) {
            $url .= '/' . implode('/', $fields);
        }
        return self::API_ENDPOINT . $url;
    }
}

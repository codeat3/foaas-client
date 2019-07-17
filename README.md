# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codeat3/foaas-client.svg?style=flat-square)](https://packagist.org/packages/codeat3/foaas-client)
[![Build Status](https://img.shields.io/travis/codeat3/foaas-client/master.svg?style=flat-square)](https://travis-ci.org/codeat3/foaas-client)
[![Quality Score](https://img.shields.io/scrutinizer/g/codeat3/foaas-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/codeat3/foaas-client)
[![Total Downloads](https://img.shields.io/packagist/dt/codeat3/foaas-client.svg?style=flat-square)](https://packagist.org/packages/codeat3/foaas-client)

A PHP Client of [FOAAS](https://foaas.com/)

## Installation

You can install the package via composer:

```bash
composer require codeat3/foaas-client
```

## Usage
Basic usage of the client

``` php
use Codeat3\FoaasClient\FoaasClient;

$foaasClient = new FoaasClient([
    'decency' => 'low', // possible decency filter options are 'low', 'medium', 'high', 'extreme' - default is no filter
]);
echo $foaasClient->what('John')->get();

// Output
What the f*ck‽ - John
```

Using format to get the response as per your need
```php
use Codeat3\FoaasClient\FoaasClient;

$foaasClient = new FoaasClient([
    'decency' => 'low',
    'responseAs' => 'array', // possible response formats are 'text' (default), 'html', 'xml', 'json', 'array'
]);
print_r($foaasClient->what('John')->get());

// Output
Array
(
    [message] => What the f*ck‽
    [subtitle] => - John
)
```

Also few helpers are provided for the type of output expected
```php
$foaasClient = new FoaasClient([
	'decency' => 'low',
]);
echo $foaasClient->what('John')->getAsText(); // What the f*ck‽ - John
echo $foaasClient->what('John')->getAsXml(); // <?xml version="1.0" encoding="UTF-8"?> <foaas:response xmlns:foaas="http://foaas.com/f*ckoff"> <foaas:message>What the f*ck‽</foaas:message> <foaas:subtitle>- John</foaas:subtitle> </foaas:response>
echo $foaasClient->what('John')->getAsHtml(); // <!DOCTYPE html> <html> <head> <title>FOAAS - What the f*ck‽ - John</title> <meta charset="utf-8"> <meta property="og:title" content="What the f*ck‽ - John"> ...
echo $foaasClient->what('John')->getAsJson(); // {"message":"What the f*ck‽","subtitle":"- John"}

print_r($foaasClient->what('John')->getAsArray());
/*
Array
(
    [message] => What the f*ck‽
    [subtitle] => - John
)
*/
```

##### Custom Responses
You can implement the custom responses and pass it in customer as per your need
```php
// Implementation
class ObjectResponse implements FoaasResponse
{
    protected $acceptHeader = 'application/json';

    public function getHeaders():string
    {
        return $this->acceptHeader;
    }

    public function response(string $response, FoaasFilter $filter)
    {
        $response = $filter->filter($response);
        return json_decode($response);
    }
}

// Use
$foaasClient = new FoaasClient([
    'decency' => 'low',
    'responseAs' => 'object',
    'responseFormats' => [
        'object' => ObjectResponse::class,
    ]
]);
var_dump($foaasClient->what('John')->get());

/*
class stdClass#27 (2) {
  public $message =>
  string(16) "What the f*ck‽"
  public $subtitle =>
  string(6) "- John"
}
*/
```
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email swapnilsarwe@gmail.com instead of using the issue tracker.

## Credits

- [Swapnil Sarwe](https://github.com/codeat3)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).

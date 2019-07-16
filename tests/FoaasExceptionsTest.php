<?php

namespace Codeat3\FoaasClient\Tests;

use Codeat3\FoaasClient\Exceptions\InvalidArgumentsException;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCallException;

class FoaasExceptionsTest extends FoaasTestCase
{

    /** @test */
    public function is_invalid_arguments_exception_caught()
    {
        $this->expectException(InvalidArgumentsException::class);
        $this->client->version('latest');
    }

    /** @test */
    public function is_invalid_method_exception_caught()
    {
        $this->expectException(InvalidMethodCallException::class);
        $this->client->d934jkfdsof9();
    }
}

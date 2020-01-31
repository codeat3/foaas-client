<?php

namespace Codeat3\FoaasClient\Tests;

use Codeat3\FoaasClient\Exceptions\InvalidArguments;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCall;
use Codeat3\FoaasClient\FoaasClient;

class FoaasExceptionsTest extends FoaasTestCase
{
    /** @test */
    public function is_invalid_arguments_exception_caught()
    {
        $this->expectException(InvalidArguments::class);
        (new FoaasClient())->version('latest');
    }

    /** @test */
    public function is_invalid_method_exception_caught()
    {
        $this->expectException(InvalidMethodCall::class);
        (new FoaasClient())->d934jkfdsof9();
    }
}

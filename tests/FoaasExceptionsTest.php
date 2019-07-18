<?php

namespace Codeat3\FoaasClient\Tests;

use Codeat3\FoaasClient\FoaasClient;
use Codeat3\FoaasClient\Exceptions\InvalidArguments;
use Codeat3\FoaasClient\Exceptions\InvalidMethodCall;

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

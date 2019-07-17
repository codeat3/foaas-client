<?php

namespace Codeat3\FoaasClient\Tests;

use Codeat3\FoaasClient\FoaasClient;

class FoaasResponseTest extends FoaasTestCase
{

    /** @test */
    public function is_response_array()
    {
        $this->assertIsString((new FoaasClient())->version()->getAsText());
    }

    /** @test */
    public function is_response_string()
    {
        $this->assertIsArray((new FoaasClient())->version()->getAsArray());
    }
}

<?php

namespace Codeat3\FoaasClient\Tests;

use Codeat3\FoaasClient\FoaasClient;

class FoaasTest extends FoaasTestCase
{

    /** @test */
    public function is_api_working()
    {
        $this->assertArrayHasKey('message', (new FoaasClient())->version()->getAsArray());
    }
}

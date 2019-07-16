<?php

namespace Codeat3\FoaasClient\Tests;

class FoaasTest extends FoaasTestCase
{

    /** @test */
    public function is_api_working()
    {
        $this->assertArrayHasKey('message', $this->client->version()->asArray());
    }
}

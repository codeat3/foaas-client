<?php

namespace Codeat3\FoaasClient\Tests;

class FoaasResponseTest extends FoaasTestCase
{

    /** @test */
    public function is_response_array()
    {
        $this->assertIsArray($this->client->version()->asArray());
    }

    /** @test */
    public function is_response_string()
    {
        $this->assertIsString($this->client->version()->asText());
    }
}

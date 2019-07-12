<?php

namespace Codeat3\FoaasClient\Tests;

use PHPUnit\Framework\TestCase;
use Codeat3\FoaasClient\FoaasClient;

class FoaasTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client  = new FoaasClient();
    }

    /** @test */
    public function is_api_working()
    {
        $this->assertArrayHasKey('message', $this->client->version());
    }
}

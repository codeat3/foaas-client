<?php

namespace Codeat3\FoaasClient\Tests;

use PHPUnit\Framework\TestCase;
use Codeat3\FoaasClient\FoaasClient;

class FoaasTestCase extends TestCase
{
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client  = new FoaasClient();
    }
}

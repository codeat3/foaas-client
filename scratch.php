<?php
use Codeat3\FoaasClient\FoaasClient;

require_once "vendor/autoload.php";

var_dump((new FoaasClient)->what('John')->asText());

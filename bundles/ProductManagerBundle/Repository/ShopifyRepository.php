<?php

namespace ProductManagerBundle\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class ShopifyRepository
{
    public function __construct(
        private LoggerInterface $logger,
        private Client $client
    )
    {
    }


}

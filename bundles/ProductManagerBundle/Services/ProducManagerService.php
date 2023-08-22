<?php

namespace ProductManagerBundle\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;

class ProductManagerService
{
    public function __construct(private LoggerInterface $logger, private ClientInterface $client)
    {
    }


}

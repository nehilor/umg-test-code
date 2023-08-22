<?php

namespace ProductManagerBundle\Listener;

use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;
use ProductManagerBundle\Repository\ShopifyRepository;
use ProductManagerBundle\Service\ProductManagerService;

class ProductUpdateListener
{
    public function __construct(
        private LoggerInterface $logger,
        private ShopifyRepository $productRepository,
        private ProductManagerService $productManagerService
    )
    {
    }

}

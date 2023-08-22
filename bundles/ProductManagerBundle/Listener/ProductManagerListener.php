<?php

namespace ProductManagerBundle\Listener;

use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;
use ProductManagerBundle\Repository\ShopifyRepository;
use ProductManagerBundle\Service\ProductManagerService;

/**
 * The ProductManagerListener class listens for DataObject events and exports products to Shopify when updated.
 */
class ProductManagerListener
{
    /**
     * @var LoggerInterface $logger The logger for recording log messages.
     */
    private LoggerInterface $logger;

    /**
     * @var ShopifyRepository $productRepository The repository for interacting with Shopify.
     */
    private ShopifyRepository $productRepository;

    /**
     * @var ProductManagerService $productManagerService The service for managing products.
     */
    private ProductManagerService $productManagerService;

    /**
     * ProductManagerListener constructor.
     *
     * @param LoggerInterface $logger The logger for recording log messages.
     * @param ShopifyRepository $productRepository The repository for interacting with Shopify.
     * @param ProductManagerService $productManagerService The service for managing products.
     */
    public function __construct(
        LoggerInterface $logger,
        ShopifyRepository $productRepository,
        ProductManagerService $productManagerService
    ) {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->productManagerService = $productManagerService;
    }

    /**
     * Handles the DataObject post-update event.
     *
     * @param ElementEventInterface $event The event element.
     */
    public function onProductManagerUpdate(ElementEventInterface $event): void
    {
        $this->logger->info('ProductManagerListener: Product Export Process Started.');

        if (!($event instanceof DataObjectEvent)) {
            $this->logger->error('ProductManagerListener: This listener can only be bound to DataObjectEvents. Please check your service configuration.');
            return;
        }

        $product = $event->getObject();

        if ($product instanceof Product) {
            $isPublished = $product->isPublished();

            if (!$isPublished) {
                $this->logger->info('ProductManagerListener: The product is not published. Skipping export.');
                return;
            }

            $shopifyProductId = $this->productRepository->getIdForSku($product->getSku());

            $this->logger->info('ProductManagerListener: Product has sku:' . $shopifyProductId);

            $this->productManagerService->saveProduct($product, $shopifyProductId);

            $this->logger->info('ProductManagerListener: Product export completed successfully.');
        }
    }
}

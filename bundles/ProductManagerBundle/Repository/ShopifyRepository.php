<?php

namespace ProductManagerBundle\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * The ShopifyRepository class interacts with the Shopify API to retrieve product information.
 */
class ShopifyRepository
{
    /**
     * @var LoggerInterface The logger for recording log messages.
     */
    private LoggerInterface $logger;

    /**
     * @var Client The HTTP client for making API requests.
     */
    private Client $client;

    /**
     * ShopifyRepository constructor.
     *
     * @param LoggerInterface $logger The logger for recording log messages.
     * @param Client $client The HTTP client for making API requests.
     */
    public function __construct(LoggerInterface $logger, Client $client)
    {
        $this->logger = $logger;
        $this->client = $client;
    }

    /**
     * Retrieves the product ID for a given SKU.
     *
     * @param string $sku The SKU to search for.
     *
     * @return int|null The product ID if found, or null if not found.
     */
    public function getIdForSku(string $sku): ?int
    {
        $products = json_decode($this->getAllProducts());

        if (!is_array($products->products)) {
            return null;
        }

        foreach ($products->products as $product) {
            foreach ($product->variants as $variant) {
                if ($sku === $variant->sku) {
                    $this->logger->info('ShopifyRepository: Found a matching SKU in Shopify => ' . $product->id);
                    return $product->id;
                }
            }
        }

        return null;
    }



    /**
     * Retrieves all products from the Shopify API.
     *
     * @return string|null The JSON response containing product data, or null on failure.
     */
    private function getAllProducts(): ?string
    {
        try {
            $shopifyUri = $_ENV['SHOPIFY_URI'];
            $shopifyToken = $_ENV['SHOPIFY_TOKEN'];
            $response = $this->client->get($shopifyUri . 'products.json', [
                'headers' => [
                    'Accept'                 => 'application/json',
                    'X-Shopify-Access-Token' => $shopifyToken,
                ],
            ]);

            if ($response->getStatusCode() > 299) {
                $this->logger->error('ShopifyRepository: Error (' . $response->getStatusCode() . ') ' . $response->getReasonPhrase());
                return null;
            }

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            $this->logger->error('ShopifyRepository Exception Thrown: (' . $e->getCode() . ') ' . $e->getMessage());
            return null;
        }
    }
}

<?php

namespace ProductManagerBundle\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;

/**
 * The ProductManagerService class handles the interaction with Shopify's API for managing products.
 */
class ProductManagerService
{
    /**
     * @var LoggerInterface The logger for recording log messages.
     */
    private LoggerInterface $logger;

    /**
     * @var ClientInterface The HTTP client for making API requests.
     */
    private ClientInterface $httpClient;

    /**
     * ProductManagerService constructor.
     *
     * @param LoggerInterface $logger The logger for recording log messages.
     * @param ClientInterface $httpClient The HTTP client for making API requests.
     */
    public function __construct(LoggerInterface $logger, ClientInterface $httpClient)
    {
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    /**
     * Saves a product to Shopify.
     *
     * @param Product $product The product to save.
     * @param int|null $existingProductId The ID of an existing product if updating, or null for creating a new product.
     */
    public function saveProduct(Product $product, ?int $existingProductId = null): void
    {
        $productData = $this->prepareProductData($product);
        $shopifyUri = $_ENV['SHOPIFY_URI'];
        $shopifyToken = $_ENV['SHOPIFY_TOKEN'];
        $method = $existingProductId ? 'PUT' : 'POST';
        $endpoint = $this->buildApiEndpoint($shopifyUri, $existingProductId);

        try {
            $response = $this->httpClient->request($method, $endpoint, [
                'form_params' => $productData,
                'headers' => [
                    'Accept'                 => 'application/json',
                    'X-Shopify-Access-Token' => $shopifyToken,
                ],
            ]);

            if ($response->getStatusCode() > 299) {
                $this->handleApiError($response);
                return;
            }
        } catch (GuzzleException $e) {
            $this->handleException($e);
            return;
        }

        $this->logger->info('ProductManagerService: Product saved successfully.');
    }

    /**
     * Prepares product data for the Shopify API request.
     *
     * @param Product $product The product to build the request for.
     *
     * @return array The product request data.
     */
    private function prepareProductData(Product $product): array
    {
        return [
            'product' => [
                'variant' => [
                    'price' => $product->getPrice(),
                    'sku'   => $product->getSku(),
                ],
                'title'        => $product->getName(),
                'product_type' => $product->getProduct_type(),
            ],
        ];
    }

    /**
     * Builds the API endpoint for creating or updating a product.
     *
     * @param string $shopifyUri The Shopify API base URI.
     * @param int|null $existingProductId The ID of an existing product if updating, or null for creating a new product.
     *
     * @return string The complete API endpoint URL.
     */
    private function buildApiEndpoint(string $shopifyUri, ?int $existingProductId): string
    {
        if ($existingProductId) {
            return $shopifyUri . "products/{$existingProductId}.json";
        }
        return $shopifyUri . 'products.json';
    }


    /**
     * Handles API errors and logs details.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The API response.
     */
    private function handleApiError($response): void
    {
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();
        $this->logger->error("ProductManagerService: API Error ({$statusCode}) {$reasonPhrase}");
    }

    /**
     * Handles exceptions and logs details.
     *
     * @param GuzzleException $exception The Guzzle exception.
     */
    private function handleException(GuzzleException $exception): void
    {
        $statusCode = $exception->getCode();
        $errorMessage = $exception->getMessage();
        $this->logger->error("ProductManagerService Exception ({$statusCode}) {$errorMessage}");
    }
}

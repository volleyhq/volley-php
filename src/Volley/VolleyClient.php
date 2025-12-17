<?php

namespace Volley;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Volley\Resources\OrganizationsResource;
use Volley\Resources\ProjectsResource;
use Volley\Resources\SourcesResource;
use Volley\Resources\DestinationsResource;
use Volley\Resources\ConnectionsResource;
use Volley\Resources\EventsResource;
use Volley\Resources\DeliveryAttemptsResource;
use Volley\Resources\WebhooksResource;

/**
 * Main client for interacting with the Volley API.
 */
class VolleyClient
{
    private const DEFAULT_BASE_URL = 'https://api.volleyhooks.com';
    private const DEFAULT_TIMEOUT = 30;

    private Client $httpClient;
    private string $apiToken;
    private string $baseUrl;
    private ?int $organizationId;

    /**
     * Organizations API resource.
     */
    public OrganizationsResource $organizations;

    /**
     * Projects API resource.
     */
    public ProjectsResource $projects;

    /**
     * Sources API resource.
     */
    public SourcesResource $sources;

    /**
     * Destinations API resource.
     */
    public DestinationsResource $destinations;

    /**
     * Connections API resource.
     */
    public ConnectionsResource $connections;

    /**
     * Events API resource.
     */
    public EventsResource $events;

    /**
     * Delivery Attempts API resource.
     */
    public DeliveryAttemptsResource $deliveryAttempts;

    /**
     * Webhooks API resource.
     */
    public WebhooksResource $webhooks;

    /**
     * Initializes a new instance of the VolleyClient class.
     *
     * @param string $apiToken Your Volley API token
     * @param string|null $baseUrl Custom base URL (defaults to https://api.volleyhooks.com)
     * @param int|null $organizationId Organization ID for all requests
     * @param Client|null $httpClient Custom Guzzle HTTP client (optional)
     */
    public function __construct(
        string $apiToken,
        ?string $baseUrl = null,
        ?int $organizationId = null,
        ?Client $httpClient = null
    ) {
        if (empty($apiToken)) {
            throw new \InvalidArgumentException('API token is required');
        }

        $this->apiToken = $apiToken;
        $this->baseUrl = $baseUrl ?? self::DEFAULT_BASE_URL;
        $this->organizationId = $organizationId;

        if ($httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => self::DEFAULT_TIMEOUT,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        } else {
            $this->httpClient = $httpClient;
        }

        // Initialize resource clients
        $this->organizations = new OrganizationsResource($this);
        $this->projects = new ProjectsResource($this);
        $this->sources = new SourcesResource($this);
        $this->destinations = new DestinationsResource($this);
        $this->connections = new ConnectionsResource($this);
        $this->events = new EventsResource($this);
        $this->deliveryAttempts = new DeliveryAttemptsResource($this);
        $this->webhooks = new WebhooksResource($this);
    }

    /**
     * Sets the organization ID for subsequent requests.
     *
     * @param int $organizationId The organization ID to use
     */
    public function setOrganizationId(int $organizationId): void
    {
        $this->organizationId = $organizationId;
    }

    /**
     * Clears the organization ID (uses default organization).
     */
    public function clearOrganizationId(): void
    {
        $this->organizationId = null;
    }

    /**
     * Gets the current organization ID.
     *
     * @return int|null The organization ID, or null if not set
     */
    public function getOrganizationId(): ?int
    {
        return $this->organizationId;
    }

    /**
     * Performs an HTTP request with authentication.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $path API path (e.g., /api/org)
     * @param array|null $data Request body data (for POST/PUT)
     * @param array|null $queryParams Query parameters
     * @return array Response data
     * @throws VolleyException If the request fails
     */
    public function request(string $method, string $path, ?array $data = null, ?array $queryParams = null): array
    {
        $options = [];

        // Add organization ID header if set
        if ($this->organizationId !== null) {
            $options['headers']['X-Organization-ID'] = (string)$this->organizationId;
        }

        // Add request body for POST/PUT
        if ($data !== null && in_array($method, ['POST', 'PUT'], true)) {
            $options['json'] = $data;
        }

        // Add query parameters
        if ($queryParams !== null) {
            $options['query'] = $queryParams;
        }

        try {
            $url = $this->baseUrl . $path;
            $response = $this->httpClient->request($method, $url, $options);
            $body = $response->getBody()->getContents();

            if (empty($body)) {
                return [];
            }

            return json_decode($body, true) ?? [];
        } catch (GuzzleException $e) {
            $statusCode = 0;
            $errorMessage = 'Request failed: ' . $e->getMessage();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                try {
                    $errorData = json_decode($body, true);
                    if (isset($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    } elseif (isset($errorData['message'])) {
                        $errorMessage = $errorData['message'];
                    }
                } catch (\JsonException $jsonException) {
                    if (!empty($body)) {
                        $errorMessage = $body;
                    }
                }
            }

            throw new VolleyException($errorMessage, $statusCode, $e);
        }
    }
}


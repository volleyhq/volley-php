<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Destination;

/**
 * Destinations API resource.
 */
class DestinationsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all destinations for a project.
     */
    public function list(int $projectId): array
    {
        $response = $this->client->request('GET', "/api/projects/{$projectId}/destinations");
        $destinationsData = $response['destinations'] ?? [];

        $destinations = [];
        foreach ($destinationsData as $destinationData) {
            $destinations[] = new Destination($destinationData);
        }

        return $destinations;
    }

    /**
     * Create a new destination.
     */
    public function create(int $projectId, string $name, string $url, ?int $eps = null): Destination
    {
        $data = [
            'name' => $name,
            'url' => $url,
        ];
        if ($eps !== null) {
            $data['eps'] = $eps;
        }

        $response = $this->client->request('POST', "/api/projects/{$projectId}/destinations", $data);
        return new Destination($response);
    }

    /**
     * Get a destination by ID.
     */
    public function get(int $projectId, int $destinationId): Destination
    {
        $response = $this->client->request('GET', "/api/projects/{$projectId}/destinations/{$destinationId}");
        return new Destination($response);
    }

    /**
     * Update a destination.
     */
    public function update(int $projectId, int $destinationId, ?string $name = null, ?string $url = null, ?int $eps = null): Destination
    {
        $data = [];
        if ($name !== null) {
            $data['name'] = $name;
        }
        if ($url !== null) {
            $data['url'] = $url;
        }
        if ($eps !== null) {
            $data['eps'] = $eps;
        }

        $response = $this->client->request('PUT', "/api/projects/{$projectId}/destinations/{$destinationId}", $data);
        return new Destination($response);
    }

    /**
     * Delete a destination.
     */
    public function delete(int $projectId, int $destinationId): void
    {
        $this->client->request('DELETE', "/api/projects/{$projectId}/destinations/{$destinationId}");
    }
}


<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Connection;

/**
 * Connections API resource.
 */
class ConnectionsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new connection.
     */
    public function create(
        int $projectId,
        int $sourceId,
        int $destinationId,
        ?string $status = null,
        ?int $eps = null,
        ?int $maxRetries = null
    ): Connection {
        $data = [
            'source_id' => $sourceId,
            'destination_id' => $destinationId,
        ];
        if ($status !== null) {
            $data['status'] = $status;
        }
        if ($eps !== null) {
            $data['eps'] = $eps;
        }
        if ($maxRetries !== null) {
            $data['max_retries'] = $maxRetries;
        }

        $response = $this->client->request('POST', "/api/projects/{$projectId}/connections", $data);
        return new Connection($response);
    }

    /**
     * Get a connection by ID.
     */
    public function get(int $connectionId): Connection
    {
        $response = $this->client->request('GET', "/api/connections/{$connectionId}");
        return new Connection($response);
    }

    /**
     * Update a connection.
     */
    public function update(int $connectionId, ?string $status = null, ?int $eps = null, ?int $maxRetries = null): Connection
    {
        $data = [];
        if ($status !== null) {
            $data['status'] = $status;
        }
        if ($eps !== null) {
            $data['eps'] = $eps;
        }
        if ($maxRetries !== null) {
            $data['max_retries'] = $maxRetries;
        }

        $response = $this->client->request('PUT', "/api/connections/{$connectionId}", $data);
        return new Connection($response);
    }

    /**
     * Delete a connection.
     */
    public function delete(int $connectionId): void
    {
        $this->client->request('DELETE', "/api/connections/{$connectionId}");
    }
}


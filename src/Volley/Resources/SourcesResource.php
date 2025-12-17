<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Source;

/**
 * Sources API resource.
 */
class SourcesResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all sources for a project.
     *
     * @param int $projectId Project ID
     * @return Source[]
     */
    public function list(int $projectId): array
    {
        $response = $this->client->request('GET', "/api/projects/{$projectId}/sources");
        $sourcesData = $response['sources'] ?? [];

        $sources = [];
        foreach ($sourcesData as $sourceData) {
            $sources[] = new Source($sourceData);
        }

        return $sources;
    }

    /**
     * Create a new source.
     */
    public function create(
        int $projectId,
        string $slug,
        string $type,
        ?int $eps = null,
        ?string $authType = null,
        ?string $authUsername = null,
        ?string $authKey = null,
        ?bool $verifySignature = null,
        ?string $webhookSecret = null
    ): Source {
        $data = [
            'slug' => $slug,
            'type' => $type,
        ];
        if ($eps !== null) {
            $data['eps'] = $eps;
        }
        if ($authType !== null) {
            $data['auth_type'] = $authType;
        }
        if ($authUsername !== null) {
            $data['auth_username'] = $authUsername;
        }
        if ($authKey !== null) {
            $data['auth_key'] = $authKey;
        }
        if ($verifySignature !== null) {
            $data['verify_signature'] = $verifySignature;
        }
        if ($webhookSecret !== null) {
            $data['webhook_secret'] = $webhookSecret;
        }

        $response = $this->client->request('POST', "/api/projects/{$projectId}/sources", $data);
        return new Source($response);
    }

    /**
     * Get a source by ID.
     */
    public function get(int $projectId, int $sourceId): Source
    {
        $response = $this->client->request('GET', "/api/projects/{$projectId}/sources/{$sourceId}");
        return new Source($response);
    }

    /**
     * Update a source.
     */
    public function update(
        int $projectId,
        int $sourceId,
        ?string $slug = null,
        ?int $eps = null,
        ?string $authType = null,
        ?string $authUsername = null,
        ?string $authKey = null,
        ?bool $verifySignature = null,
        ?string $webhookSecret = null
    ): Source {
        $data = [];
        if ($slug !== null) {
            $data['slug'] = $slug;
        }
        if ($eps !== null) {
            $data['eps'] = $eps;
        }
        if ($authType !== null) {
            $data['auth_type'] = $authType;
        }
        if ($authUsername !== null) {
            $data['auth_username'] = $authUsername;
        }
        if ($authKey !== null) {
            $data['auth_key'] = $authKey;
        }
        if ($verifySignature !== null) {
            $data['verify_signature'] = $verifySignature;
        }
        if ($webhookSecret !== null) {
            $data['webhook_secret'] = $webhookSecret;
        }

        $response = $this->client->request('PUT', "/api/projects/{$projectId}/sources/{$sourceId}", $data);
        return new Source($response);
    }

    /**
     * Delete a source.
     */
    public function delete(int $projectId, int $sourceId): void
    {
        $this->client->request('DELETE', "/api/projects/{$projectId}/sources/{$sourceId}");
    }
}


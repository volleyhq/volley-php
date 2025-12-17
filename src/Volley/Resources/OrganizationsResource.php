<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Organization;

/**
 * Organizations API resource.
 */
class OrganizationsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all organizations the user has access to.
     *
     * @return Organization[]
     */
    public function list(): array
    {
        $response = $this->client->request('GET', '/api/org/list');
        $orgsData = $response['organizations'] ?? [];

        $organizations = [];
        foreach ($orgsData as $orgData) {
            $organizations[] = new Organization($orgData);
        }

        return $organizations;
    }

    /**
     * Get the current organization.
     *
     * @param int|null $organizationId Optional organization ID. If null, uses default organization.
     * @return Organization
     */
    public function get(?int $organizationId = null): Organization
    {
        $originalOrgId = $this->client->getOrganizationId();
        if ($organizationId !== null) {
            $this->client->setOrganizationId($organizationId);
        }

        try {
            $response = $this->client->request('GET', '/api/org');
            // GetOrganization returns id, name, slug, role (not account_id, created_at)
            $orgData = [
                'id' => $response['id'] ?? 0,
                'name' => $response['name'] ?? '',
                'slug' => $response['slug'] ?? '',
                'role' => $response['role'] ?? '',
                'account_id' => $response['account_id'] ?? null,
                'created_at' => $response['created_at'] ?? null,
            ];
            return new Organization($orgData);
        } finally {
            if ($originalOrgId !== null) {
                $this->client->setOrganizationId($originalOrgId);
            } else {
                $this->client->clearOrganizationId();
            }
        }
    }

    /**
     * Create a new organization.
     *
     * @param string $name Organization name
     * @return Organization
     */
    public function create(string $name): Organization
    {
        $data = ['name' => $name];
        $response = $this->client->request('POST', '/api/org', $data);
        return new Organization($response);
    }
}


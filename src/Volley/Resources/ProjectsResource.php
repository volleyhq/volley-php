<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Project;

/**
 * Projects API resource.
 */
class ProjectsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all projects in the current organization.
     *
     * @return Project[]
     */
    public function list(): array
    {
        $response = $this->client->request('GET', '/api/projects');
        $projectsData = $response['projects'] ?? [];

        $projects = [];
        foreach ($projectsData as $projectData) {
            $projects[] = new Project($projectData);
        }

        return $projects;
    }

    /**
     * Create a new project.
     *
     * @param string $name Project name
     * @return Project
     */
    public function create(string $name): Project
    {
        $data = ['name' => $name];
        $response = $this->client->request('POST', '/api/projects', $data);
        return new Project($response);
    }

    /**
     * Update a project.
     *
     * @param int $projectId Project ID
     * @param string $name Updated project name
     * @return Project
     */
    public function update(int $projectId, string $name): Project
    {
        $data = ['name' => $name];
        $response = $this->client->request('PUT', "/api/projects/{$projectId}", $data);
        return new Project($response);
    }

    /**
     * Delete a project.
     *
     * @param int $projectId Project ID to delete
     */
    public function delete(int $projectId): void
    {
        $this->client->request('DELETE', "/api/projects/{$projectId}");
    }
}


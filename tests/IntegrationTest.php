<?php

namespace Volley\Tests;

use PHPUnit\Framework\TestCase;
use Volley\VolleyClient;

/**
 * Integration tests that make real API calls.
 * These tests are skipped unless VOLLEY_API_TOKEN is set.
 */
class IntegrationTest extends TestCase
{
    private ?VolleyClient $client = null;

    protected function setUp(): void
    {
        $apiToken = getenv('VOLLEY_API_TOKEN');
        if (!$apiToken) {
            $this->markTestSkipped('VOLLEY_API_TOKEN environment variable is not set');
        }

        $this->client = new VolleyClient($apiToken);
    }

    public function testListOrganizations()
    {
        $orgs = $this->client->organizations->list();
        $this->assertIsArray($orgs);
        // May be empty for new accounts, which is OK
    }

    public function testGetOrganization()
    {
        $orgs = $this->client->organizations->list();
        if (empty($orgs)) {
            $this->markTestSkipped('No organizations available');
        }

        $org = $this->client->organizations->get($orgs[0]->id);
        $this->assertEquals($orgs[0]->id, $org->id);
    }

    public function testListProjects()
    {
        $orgs = $this->client->organizations->list();
        if (empty($orgs)) {
            $this->markTestSkipped('No organizations available');
        }

        $this->client->setOrganizationId($orgs[0]->id);
        $projects = $this->client->projects->list();
        $this->assertIsArray($projects);
        // May be empty, which is OK
    }

    public function testListSources()
    {
        $orgs = $this->client->organizations->list();
        if (empty($orgs)) {
            $this->markTestSkipped('No organizations available');
        }

        $this->client->setOrganizationId($orgs[0]->id);
        $projects = $this->client->projects->list();
        if (empty($projects)) {
            $this->markTestSkipped('No projects available');
        }

        $sources = $this->client->sources->list($projects[0]->id);
        $this->assertIsArray($sources);
        // May be empty, which is OK
    }
}


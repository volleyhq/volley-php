<?php

namespace Volley\Tests;

use PHPUnit\Framework\TestCase;
use Volley\VolleyClient;
use Volley\Models\Organization;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class OrganizationsResourceTest extends TestCase
{
    public function testListOrganizations()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'organizations' => [
                    ['id' => 1, 'name' => 'Org 1', 'slug' => 'org-1', 'role' => 'admin'],
                    ['id' => 2, 'name' => 'Org 2', 'slug' => 'org-2', 'role' => 'member'],
                ],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $client = new VolleyClient('test-token', null, null, $httpClient);

        $orgs = $client->organizations->list();
        $this->assertCount(2, $orgs);
        $this->assertInstanceOf(Organization::class, $orgs[0]);
        $this->assertEquals('Org 1', $orgs[0]->name);
    }

    public function testGetOrganization()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => 1,
                'name' => 'Test Org',
                'slug' => 'test-org',
                'role' => 'admin',
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $client = new VolleyClient('test-token', null, null, $httpClient);

        $org = $client->organizations->get();
        $this->assertInstanceOf(Organization::class, $org);
        $this->assertEquals('Test Org', $org->name);
    }

    public function testCreateOrganization()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'id' => 1,
                'name' => 'New Org',
                'slug' => 'new-org',
                'role' => 'admin',
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $client = new VolleyClient('test-token', null, null, $httpClient);

        $org = $client->organizations->create('New Org');
        $this->assertInstanceOf(Organization::class, $org);
        $this->assertEquals('New Org', $org->name);
    }
}


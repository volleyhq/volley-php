<?php

namespace Volley\Tests;

use PHPUnit\Framework\TestCase;
use Volley\VolleyClient;
use Volley\VolleyException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class VolleyClientTest extends TestCase
{
    public function testClientInitialization()
    {
        $client = new VolleyClient('test-token');
        $this->assertInstanceOf(VolleyClient::class, $client);
        $this->assertNull($client->getOrganizationId());
    }

    public function testClientInitializationWithOrganizationId()
    {
        $client = new VolleyClient('test-token', null, 123);
        $this->assertEquals(123, $client->getOrganizationId());
    }

    public function testClientInitializationWithCustomBaseUrl()
    {
        $client = new VolleyClient('test-token', 'https://custom.api.com');
        $this->assertInstanceOf(VolleyClient::class, $client);
    }

    public function testSetOrganizationId()
    {
        $client = new VolleyClient('test-token');
        $client->setOrganizationId(456);
        $this->assertEquals(456, $client->getOrganizationId());
    }

    public function testClearOrganizationId()
    {
        $client = new VolleyClient('test-token', null, 123);
        $client->clearOrganizationId();
        $this->assertNull($client->getOrganizationId());
    }

    public function testEmptyApiTokenThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new VolleyClient('');
    }

    public function testSuccessfulRequest()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 1, 'name' => 'Test Org'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $client = new VolleyClient('test-token', null, null, $httpClient);
        $response = $client->request('GET', '/api/org');

        $this->assertEquals(1, $response['id']);
        $this->assertEquals('Test Org', $response['name']);
    }

    public function testRequestWithOrganizationHeader()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 1])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $client = new VolleyClient('test-token', null, 789, $httpClient);
        $client->request('GET', '/api/org');

        $request = $mock->getLastRequest();
        $this->assertEquals('789', $request->getHeaderLine('X-Organization-ID'));
    }

    public function testRequestThrowsVolleyExceptionOnError()
    {
        $mock = new MockHandler([
            new Response(400, [], json_encode(['error' => 'Bad request'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $client = new VolleyClient('test-token', null, null, $httpClient);

        $this->expectException(VolleyException::class);
        $this->expectExceptionMessage('Bad request');
        $client->request('GET', '/api/org');
    }

    public function testRequestWithPostData()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode(['id' => 1, 'name' => 'New Org'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $client = new VolleyClient('test-token', null, null, $httpClient);
        $response = $client->request('POST', '/api/org', ['name' => 'New Org']);

        $this->assertEquals(1, $response['id']);
        $this->assertEquals('New Org', $response['name']);
    }
}


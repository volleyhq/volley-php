# Testing the Volley PHP SDK

This document describes how to run tests for the Volley PHP SDK.

## Prerequisites

1. Install dependencies:
```bash
composer install
```

2. Ensure PHPUnit is installed (included in dev dependencies):
```bash
composer require --dev phpunit/phpunit
```

## Running Tests

### Unit Tests

Unit tests use mocked HTTP responses and don't require a real API token:

```bash
vendor/bin/phpunit tests/
```

Or run specific test files:

```bash
vendor/bin/phpunit tests/VolleyClientTest.php
vendor/bin/phpunit tests/OrganizationsResourceTest.php
```

### Integration Tests

Integration tests make real API calls to the Volley API. These tests are skipped unless `VOLLEY_API_TOKEN` is set.

To run integration tests:

1. Set your API token:
```bash
export VOLLEY_API_TOKEN=your-api-token-here
```

2. Run integration tests:
```bash
vendor/bin/phpunit tests/IntegrationTest.php
```

**Note**: Integration tests may create, modify, or delete resources in your account. Use a test account or be prepared to clean up test data.

## Test Coverage

To generate a test coverage report:

```bash
vendor/bin/phpunit --coverage-html coverage/
```

This generates an HTML coverage report in the `coverage/` directory.

## Writing Tests

### Unit Tests

Unit tests should mock HTTP responses using Guzzle's `MockHandler`:

```php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

$mock = new MockHandler([
    new Response(200, [], json_encode(['id' => 1, 'name' => 'Test'])),
]);

$handlerStack = HandlerStack::create($mock);
$httpClient = new Client(['handler' => $handlerStack]);

$client = new VolleyClient('test-token', null, null, $httpClient);
```

### Integration Tests

Integration tests should:
- Check for `VOLLEY_API_TOKEN` environment variable
- Skip if not set
- Clean up any resources created during tests
- Use descriptive test names

Example:

```php
public function testListOrganizations()
{
    $apiToken = getenv('VOLLEY_API_TOKEN');
    if (!$apiToken) {
        $this->markTestSkipped('VOLLEY_API_TOKEN not set');
    }

    $client = new VolleyClient($apiToken);
    $orgs = $client->organizations->list();
    $this->assertIsArray($orgs);
}
```

## Continuous Integration

The SDK includes a PHPUnit configuration file (`phpunit.xml`) that can be used in CI/CD pipelines. The configuration:

- Runs all tests in the `tests/` directory
- Generates coverage reports
- Excludes integration tests by default (unless `VOLLEY_API_TOKEN` is set)


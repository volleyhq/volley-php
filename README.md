# Volley PHP SDK

Official PHP SDK for the Volley API. This SDK provides a convenient way to interact with the Volley webhook infrastructure API.

[Volley](https://volleyhooks.com) is a webhook infrastructure platform that provides reliable webhook delivery, rate limiting, retries, monitoring, and more.

## Resources

- **Documentation**: [https://docs.volleyhooks.com](https://docs.volleyhooks.com)
- **Getting Started Guide**: [https://docs.volleyhooks.com/getting-started](https://docs.volleyhooks.com/getting-started)
- **API Reference**: [https://docs.volleyhooks.com/api](https://docs.volleyhooks.com/api)
- **Authentication Guide**: [https://docs.volleyhooks.com/authentication](https://docs.volleyhooks.com/authentication)
- **Security Guide**: [https://docs.volleyhooks.com/security](https://docs.volleyhooks.com/security)
- **Console**: [https://app.volleyhooks.com](https://app.volleyhooks.com)
- **Website**: [https://volleyhooks.com](https://volleyhooks.com)

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

```bash
composer require volleyhq/volley-php
```

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use Volley\VolleyClient;

// Create a client with your API token
$client = new VolleyClient('your-api-token');

// Optionally set organization context
$client->setOrganizationId(123);

// List organizations
$orgs = $client->organizations->list();
foreach ($orgs as $org) {
    echo "Organization: {$org->name} (ID: {$org->id})\n";
}
```

## Authentication

Volley uses API tokens for authentication. These are long-lived tokens designed for programmatic access.

### Getting Your API Token

1. Log in to the [Volley Console](https://app.volleyhooks.com)
2. Navigate to **Settings → Account → API Token**
3. Click **View Token** (you may need to verify your password)
4. Copy the token and store it securely

**Important**: API tokens are non-expiring and provide full access to your account. Keep them secure and rotate them if compromised. See the [Security Guide](https://docs.volleyhooks.com/security) for best practices.

```php
$client = new VolleyClient('your-api-token');
```

For more details on authentication, API tokens, and security, see the [Authentication Guide](https://docs.volleyhooks.com/authentication) and [Security Guide](https://docs.volleyhooks.com/security).

## Organization Context

When you have multiple organizations, you need to specify which organization context to use for API requests. The API verifies that resources (like projects) belong to the specified organization.

You can set the organization context in two ways:

```php
// Method 1: Set organization ID for all subsequent requests
$client->setOrganizationId(123);

// Method 2: Create client with organization ID
$client = new VolleyClient('your-api-token', null, 123);

// Clear organization context (uses first accessible organization)
$client->clearOrganizationId();
```

**Note**: If you don't set an organization ID, the API uses your first accessible organization by default. For more details, see the [API Reference - Organization Context](https://docs.volleyhooks.com/api#organization-context).

## Examples

### Organizations

```php
use Volley\VolleyClient;

// List all organizations
$orgs = $client->organizations->list();

// Get current organization
$org = $client->organizations->get(); // null = use default

// Create organization
$newOrg = $client->organizations->create('My Organization');
```

### Projects

```php
// List projects (requires organization context)
$client->setOrganizationId(123);
$projects = $client->projects->list();

// Create project
$project = $client->projects->create('My Project');

// Update project
$updated = $client->projects->update($project->id, 'Updated Name');

// Delete project
$client->projects->delete($project->id);
```

### Sources

```php
// List sources for a project
$sources = $client->sources->list($projectId);

// Create source
$source = $client->sources->create(
    $projectId,
    'my-source',
    'webhook',
    eps: 100,
    verifySignature: true
);

// Get source
$source = $client->sources->get($projectId, $sourceId);

// Update source
$updated = $client->sources->update(
    $projectId,
    $sourceId,
    slug: 'new-slug',
    eps: 200
);

// Delete source
$client->sources->delete($projectId, $sourceId);
```

### Destinations

```php
// List destinations for a project
$destinations = $client->destinations->list($projectId);

// Create destination
$destination = $client->destinations->create(
    $projectId,
    'My Destination',
    'https://example.com/webhook',
    eps: 50
);

// Get destination
$destination = $client->destinations->get($projectId, $destinationId);

// Update destination
$updated = $client->destinations->update(
    $projectId,
    $destinationId,
    name: 'Updated Name',
    url: 'https://new.example.com/webhook'
);

// Delete destination
$client->destinations->delete($projectId, $destinationId);
```

### Connections

```php
// Create connection
$connection = $client->connections->create(
    $projectId,
    $sourceId,
    $destinationId,
    status: 'active',
    maxRetries: 3
);

// Get connection
$connection = $client->connections->get($connectionId);

// Update connection
$updated = $client->connections->update(
    $connectionId,
    status: 'paused',
    eps: 100
);

// Delete connection
$client->connections->delete($connectionId);
```

### Events

```php
// List events with filters
$result = $client->events->list(
    $projectId,
    limit: 10,
    offset: 0,
    sourceId: 'src_123',
    status: 'failed'
);

foreach ($result['events'] as $event) {
    echo "Event ID: {$event->eventId}, Status: {$event->status}\n";
}

// Get event by ID
$event = $client->events->get($projectId, 'evt_abc123xyz');

// Replay a failed event
$message = $client->events->replay('evt_abc123xyz');
```

### Delivery Attempts

```php
// List delivery attempts with filters
$result = $client->deliveryAttempts->list(
    projectId: $projectId,
    eventId: 'evt_abc123xyz',
    status: 'failed',
    limit: 20
);

foreach ($result['attempts'] as $attempt) {
    echo "Attempt ID: {$attempt->id}, Status: {$attempt->status}\n";
}
```

### Webhooks

```php
// Send a webhook
$message = $client->webhooks->send(
    $sourceId,
    $destinationId,
    ['key' => 'value', 'data' => 'test'],
    ['X-Custom-Header' => 'value']
);
```

## Error Handling

The SDK throws `VolleyException` for API errors. The exception includes the HTTP status code and error message:

```php
use Volley\VolleyClient;
use Volley\VolleyException;

try {
    $org = $client->organizations->get(999);
} catch (VolleyException $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "Status Code: {$e->getStatusCode()}\n";
    
    // Check specific error types
    if ($e->isNotFound()) {
        echo "Organization not found\n";
    } elseif ($e->isUnauthorized()) {
        echo "Authentication failed\n";
    }
}
```

## Client Options

You can customize the client with additional options:

```php
use Volley\VolleyClient;
use GuzzleHttp\Client;

// Custom base URL (for testing)
$client = new VolleyClient(
    'your-api-token',
    'https://api.staging.volleyhooks.com'
);

// Custom HTTP client
$httpClient = new Client([
    'timeout' => 60,
    'verify' => false, // Only for testing
]);
$client = new VolleyClient(
    'your-api-token',
    null,
    null,
    $httpClient
);
```

## License

MIT License. See [LICENSE](LICENSE) file for details.

## Support

- **Documentation**: [https://docs.volleyhooks.com](https://docs.volleyhooks.com)
- **Issues**: [https://github.com/volleyhq/volley-php/issues](https://github.com/volleyhq/volley-php/issues)
- **Email**: support@volleyhooks.com


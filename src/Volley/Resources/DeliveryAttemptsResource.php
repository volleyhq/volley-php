<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\DeliveryAttempt;

/**
 * Delivery Attempts API resource.
 */
class DeliveryAttemptsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List delivery attempts.
     *
     * @return array{attempts: DeliveryAttempt[], total: int, limit: int, offset: int}
     */
    public function list(
        ?int $projectId = null,
        ?string $eventId = null,
        ?int $connectionId = null,
        ?string $status = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $queryParams = [];
        if ($projectId !== null) {
            $queryParams['project_id'] = (string)$projectId;
        }
        if ($eventId !== null) {
            $queryParams['event_id'] = $eventId;
        }
        if ($connectionId !== null) {
            $queryParams['connection_id'] = (string)$connectionId;
        }
        if ($status !== null) {
            $queryParams['status'] = $status;
        }
        if ($limit !== null) {
            $queryParams['limit'] = (string)$limit;
        }
        if ($offset !== null) {
            $queryParams['offset'] = (string)$offset;
        }

        $response = $this->client->request('GET', '/api/delivery-attempts', null, $queryParams);

        $attemptsData = $response['attempts'] ?? [];
        $attempts = [];
        foreach ($attemptsData as $attemptData) {
            $attempts[] = new DeliveryAttempt($attemptData);
        }

        return [
            'attempts' => $attempts,
            'total' => $response['total'] ?? 0,
            'limit' => $response['limit'] ?? 0,
            'offset' => $response['offset'] ?? 0,
        ];
    }
}


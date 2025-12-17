<?php

namespace Volley\Resources;

use Volley\VolleyClient;
use Volley\Models\Event;

/**
 * Events API resource.
 */
class EventsResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * List events for a project.
     *
     * @return array{events: Event[], total: int, limit: int, offset: int}
     */
    public function list(
        int $projectId,
        ?int $limit = null,
        ?int $offset = null,
        ?string $sourceId = null,
        ?string $status = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $queryParams = [];
        if ($limit !== null) {
            $queryParams['limit'] = (string)$limit;
        }
        if ($offset !== null) {
            $queryParams['offset'] = (string)$offset;
        }
        if ($sourceId !== null) {
            $queryParams['source_id'] = $sourceId;
        }
        if ($status !== null) {
            $queryParams['status'] = $status;
        }
        if ($startDate !== null) {
            $queryParams['start_date'] = $startDate;
        }
        if ($endDate !== null) {
            $queryParams['end_date'] = $endDate;
        }

        $response = $this->client->request('GET', "/api/projects/{$projectId}/events", null, $queryParams);

        $eventsData = $response['requests'] ?? [];
        $events = [];
        foreach ($eventsData as $eventData) {
            $events[] = new Event($eventData);
        }

        return [
            'events' => $events,
            'total' => $response['total'] ?? 0,
            'limit' => $response['limit'] ?? 0,
            'offset' => $response['offset'] ?? 0,
        ];
    }

    /**
     * Get an event by ID.
     */
    public function get(int $projectId, string $eventId): Event
    {
        $response = $this->client->request('GET', "/api/projects/{$projectId}/events/{$eventId}");
        return new Event($response['request'] ?? []);
    }

    /**
     * Replay an event.
     */
    public function replay(string $eventId, ?int $destinationId = null, ?int $connectionId = null): string
    {
        $data = ['event_id' => $eventId];
        if ($destinationId !== null) {
            $data['destination_id'] = $destinationId;
        }
        if ($connectionId !== null) {
            $data['connection_id'] = $connectionId;
        }

        $response = $this->client->request('POST', '/api/replay-event', $data);
        return $response['message'] ?? 'Event replayed';
    }
}


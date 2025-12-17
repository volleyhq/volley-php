<?php

namespace Volley\Models;

/**
 * Event model.
 */
class Event
{
    public int $id;
    public string $eventId;
    public int $sourceId;
    public int $projectId;
    public string $rawBody;
    public array $headers;
    public string $status;
    public ?array $deliveryAttempts;
    public string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->eventId = $data['event_id'] ?? '';
        $this->sourceId = $data['source_id'] ?? 0;
        $this->projectId = $data['project_id'] ?? 0;
        $this->rawBody = $data['raw_body'] ?? '';
        $this->headers = $data['headers'] ?? [];
        $this->status = $data['status'] ?? '';
        $this->deliveryAttempts = $data['delivery_attempts'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
    }
}


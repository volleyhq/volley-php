<?php

namespace Volley\Models;

/**
 * Delivery Attempt model.
 */
class DeliveryAttempt
{
    public int $id;
    public string $eventId;
    public int $connectionId;
    public string $status;
    public int $statusCode;
    public ?string $errorReason;
    public int $durationMs;
    public string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->eventId = $data['event_id'] ?? '';
        $this->connectionId = $data['connection_id'] ?? 0;
        $this->status = $data['status'] ?? '';
        $this->statusCode = $data['status_code'] ?? 0;
        $this->errorReason = $data['error_reason'] ?? null;
        $this->durationMs = $data['duration_ms'] ?? 0;
        $this->createdAt = $data['created_at'] ?? '';
    }
}


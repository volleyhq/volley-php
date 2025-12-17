<?php

namespace Volley\Models;

/**
 * Connection model.
 */
class Connection
{
    public int $id;
    public int $sourceId;
    public int $destinationId;
    public string $status;
    public int $eps;
    public int $maxRetries;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->sourceId = $data['source_id'] ?? 0;
        $this->destinationId = $data['destination_id'] ?? 0;
        $this->status = $data['status'] ?? '';
        $this->eps = $data['eps'] ?? 0;
        $this->maxRetries = $data['max_retries'] ?? 0;
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
    }
}


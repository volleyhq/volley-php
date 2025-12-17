<?php

namespace Volley\Models;

/**
 * Destination model.
 */
class Destination
{
    public int $id;
    public string $name;
    public string $url;
    public int $eps;
    public string $status;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->eps = $data['eps'] ?? 0;
        $this->status = $data['status'] ?? '';
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
    }
}


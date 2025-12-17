<?php

namespace Volley\Models;

/**
 * Project model.
 */
class Project
{
    public int $id;
    public string $name;
    public int $organizationId;
    public ?int $userId;
    public bool $isDefault;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->organizationId = $data['organization_id'] ?? 0;
        $this->userId = $data['user_id'] ?? null;
        $this->isDefault = $data['is_default'] ?? false;
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
    }
}


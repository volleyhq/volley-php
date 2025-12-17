<?php

namespace Volley\Models;

/**
 * Organization model.
 */
class Organization
{
    public int $id;
    public string $name;
    public string $slug;
    public string $role;
    public ?int $accountId;
    public ?string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->accountId = $data['account_id'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
    }
}


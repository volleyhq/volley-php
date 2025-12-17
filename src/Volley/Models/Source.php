<?php

namespace Volley\Models;

/**
 * Source model.
 */
class Source
{
    public int $id;
    public string $slug;
    public string $ingestionId;
    public string $type;
    public int $eps;
    public string $status;
    public int $connectionCount;
    public string $authType;
    public bool $verifySignature;
    public bool $webhookSecretSet;
    public ?string $authUsername;
    public ?string $authKeyName;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->slug = $data['slug'] ?? '';
        $this->ingestionId = $data['ingestion_id'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->eps = $data['eps'] ?? 0;
        $this->status = $data['status'] ?? '';
        $this->connectionCount = $data['connection_count'] ?? 0;
        $this->authType = $data['auth_type'] ?? '';
        $this->verifySignature = $data['verify_signature'] ?? false;
        $this->webhookSecretSet = $data['webhook_secret_set'] ?? false;
        $this->authUsername = $data['auth_username'] ?? null;
        $this->authKeyName = $data['auth_key_name'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
    }
}


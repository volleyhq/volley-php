<?php

namespace Volley\Resources;

use Volley\VolleyClient;

/**
 * Webhooks API resource.
 */
class WebhooksResource
{
    private VolleyClient $client;

    public function __construct(VolleyClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a webhook.
     */
    public function send(int $sourceId, int $destinationId, array $body, ?array $headers = null): string
    {
        $data = [
            'source_id' => $sourceId,
            'destination_id' => $destinationId,
            'body' => $body,
        ];
        if ($headers !== null) {
            $data['headers'] = $headers;
        }

        $response = $this->client->request('POST', '/api/webhooks', $data);
        return $response['message'] ?? 'Webhook sent';
    }
}


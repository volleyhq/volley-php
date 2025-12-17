<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Volley\VolleyClient;

// Initialize the client
$client = new VolleyClient('your-api-token');

// List organizations
$organizations = $client->organizations->list();
foreach ($organizations as $org) {
    echo "Organization: {$org->name} (ID: {$org->id})\n";
}

// Get the first organization
if (!empty($organizations)) {
    $org = $client->organizations->get($organizations[0]->id);
    echo "Current organization: {$org->name}\n";

    // Set organization context
    $client->setOrganizationId($org->id);

    // List projects
    $projects = $client->projects->list();
    foreach ($projects as $project) {
        echo "Project: {$project->name} (ID: {$project->id})\n";
    }

    // Create a new project
    if (!empty($projects)) {
        $project = $projects[0];
        
        // List sources
        $sources = $client->sources->list($project->id);
        echo "Found " . count($sources) . " sources\n";

        // List events
        $eventsResult = $client->events->list($project->id, limit: 10);
        echo "Found {$eventsResult['total']} events\n";
    }
}


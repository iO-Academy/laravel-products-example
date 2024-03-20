<?php

namespace App\Services;

// MUST follow SRP
    // A service should have one responsibility
    // if you're not sure whether or not something should belong to the service, just make a new one
class JsonResponseService
{
    public function getFormat(string $message, mixed $data = false): array
    {
        $result = [
            'message' => $message,
        ];

        if ($data) {
            $result['data'] = $data;
        }

        return $result;
    }
}

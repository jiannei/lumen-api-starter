<?php

return [

    'query' => [
        'enabled' => env('LOG_QUERY', false),

        // Only record queries that are slower than the following time
        // Unit: milliseconds
        'slower_than' => 0,
    ],

    'request' => [
        'enabled' => env('LOG_REQUEST', false),
    ],
];

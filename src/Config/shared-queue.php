<?php
// src/Config/shared-queue.php

return [
    'redis_host' => env('QUEUE_REDIS_HOST', '127.0.0.1'),
    'redis_port' => env('QUEUE_REDIS_PORT', 6379),
    'redis_password' => env('QUEUE_REDIS_PASSWORD', null),
    'queue_name' => env('SHARED_QUEUE_NAME', 'shared_queue'),
];
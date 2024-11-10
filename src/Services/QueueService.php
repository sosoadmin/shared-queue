<?php
// src/Services/QueueService.php

namespace SharedQueue\Services;

use Predis\Client;
use SharedQueue\Contracts\QueueableInterface;

class QueueService
{
    protected $redis;
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => $config['redis_host'],
            'port' => $config['redis_port'],
            'password' => $config['redis_password']
        ]);
    }

    public function push(QueueableInterface $job): bool
    {
        $payload = $this->createPayload($job);
        return $this->pushRaw($payload, $job->getQueueName());
    }

    protected function createPayload(QueueableInterface $job): string
    {
        $payload = [
            'displayName' => get_class($job),
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'maxTries' => null,
            'timeout' => null,
            'timeoutAt' => null,
            'data' => [
                'commandName' => get_class($job),
                'command' => serialize([
                    'data' => $job->getJobData()
                ])
            ],
        ];

        return json_encode($payload);
    }

    protected function pushRaw(string $payload, string $queue): bool
    {
        try {
            $this->redis->rpush("queues:{$queue}", $payload);
            return true;
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to push job to queue: " . $e->getMessage());
        }
    }
    public function getQueueSize(string $queue): int
    {
        return $this->redis->llen("queues:{$queue}");
    }

    public function getQueueInfo(string $queue): array
    {
        return [
            'size' => $this->getQueueSize($queue),
            'last_job_at' => $this->redis->get("queue:{$queue}:last_job_at"),
        ];
    }
}
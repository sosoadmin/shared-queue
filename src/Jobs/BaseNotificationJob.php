<?php
// src/Jobs/BaseNotificationJob.php

namespace SharedQueue\Jobs;

use SharedQueue\Contracts\QueueableInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class BaseNotificationJob implements ShouldQueue, QueueableInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $queueName = 'shared_queue';

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->onQueue($this->queueName);
    }

    public function getJobData(): array
    {
        return $this->data;
    }

    public function getQueueName(): string
    {
        return $this->queueName;
    }

    abstract public function handle();
}
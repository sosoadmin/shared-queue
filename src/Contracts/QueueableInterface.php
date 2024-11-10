<?php
// src/Contracts/QueueableInterface.php

namespace SharedQueue\Contracts;

interface QueueableInterface
{
    public function getJobData(): array;
    public function getQueueName(): string;
}
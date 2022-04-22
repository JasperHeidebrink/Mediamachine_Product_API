<?php

namespace DPG\WordPress\EventApi\Models;

class Event
{
    /**
     * @var int
     */
    protected int $id;
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $status = 'hidden';
    /**
     * @var bool
     */
    protected bool $archived = false;

    public function __construct(
        int $id,
        string $title,
        string $status = 'hidden',
        string $archived = ''
    ) {
        $this->status   = $status;
        $this->id       = $id;
        $this->title    = $title;
        $this->archived = ! empty($archived);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->archived;
    }
}

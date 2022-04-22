<?php

namespace DPG\WordPress\EventApi\Models;

class Edition
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
    protected string $start;
    /**
     * @var string
     */
    protected string $end;
    /**
     * @var string
     */
    protected string $content;
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
        string $start,
        string $end,
        string $content,
        string $status = 'hidden',
        string $archived = ''
    ) {
        $this->status   = $status;
        $this->id       = $id;
        $this->title    = $title;
        $this->start    = $start;
        $this->end      = $end;
        $this->content  = $content;
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

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}

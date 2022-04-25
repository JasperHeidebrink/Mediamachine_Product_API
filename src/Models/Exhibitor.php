<?php

namespace DPG\WordPress\EventApi\Models;

class Exhibitor
{
    /**
     * @var string
     */
    protected string $id;
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $url;

    public function __construct(
        string $id,
        string $title,
        string $url,
    ) {
        $this->id    = $id;
        $this->title = $title;
        $this->url   = $url;
    }

    /**
     * @return string
     */
    public function getId(): string
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
    public function getUrl(): string
    {
        return $this->url;
    }
}

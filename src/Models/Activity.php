<?php

namespace DPG\WordPress\EventApi\Models;

class Activity {
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
	protected string $type;
	/**
	 * @var int
	 */
	protected int $active = 1;
	/**
	 * @var string
	 */
	protected string $dateActive;
	/**
	 * @var string
	 */
	protected string $media;
	/**
	 * @var string
	 */
	protected string $location;
	/**
	 * @var string
	 */
	protected string $sublocation;
	/**
	 * @var string
	 */
	protected string $readmore;
	/**
	 * @var string
	 */
	protected string $website;
	/**
	 * @var array
	 */
	protected array $timebox;

	public function __construct(
		string $id,
		string $title,
		string $type = 'Programma',
		string $active = '',
		string $dateActive = '',
		string $media = '',
		string $location = '',
		string $sublocation = '',
		string $readmore = '',
		string $website = '',
		array $timebox = [],
	) {
		$this->id         = $id;
		$this->type       = $type;
		$this->title      = $title;
		$this->active     = $active ?? 1;
		$this->dateActive = $dateActive;
		$this->media   = $media;
		$this->location   = $location;
		$this->sublocation   = $sublocation;
		$this->readmore   = $readmore;
		$this->website    = $website;
		$this->timebox    = $timebox ?? [];
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @return int
	 */
	public function getActive(): int {
		return $this->active;
	}

	/**
	 * @return string
	 */
	public function getDateActive(): string {
		return $this->dateActive;
	}

	/**
	 * @return string
	 */
	public function getMedia(): string {
		return $this->media;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string {
		return $this->location;
	}

	/**
	 * @return string
	 */
	public function getSublocation(): string {
		return $this->sublocation;
	}

	/**
	 * @return string
	 */
	public function getReadmore(): string {
		return $this->readmore;
	}

	/**
	 * @return string
	 */
	public function getWebsite(): string {
		return $this->website;
	}

	/**
	 * @return array
	 */
	public function getTimebox(): array {
		return $this->timebox;
	}
}

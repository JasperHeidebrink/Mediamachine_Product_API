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
	protected string $location;

	public function __construct(
		string $id,
		string $title,
		string $type = 'Programma',
		string $active = '',
		string $dateActive = '',
		string $location = ''
	) {
		$this->id         = $id;
		$this->type       = $type;
		$this->title      = $title;
		$this->active     = $active ?? 1;
		$this->dateActive = $dateActive;
		$this->location   = $location;
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
	public function getLocation(): string {
		return $this->location;
	}
}

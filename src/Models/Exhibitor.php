<?php

namespace DPG\WordPress\EventApi\Models;

class Exhibitor {
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
	/**
	 * @var array
	 */
	protected array $stand;
	/**
	 * @var array
	 */
	protected array $branches;

	public function __construct(
		string $id,
		string $title,
		string $url,
		array $stand,
		array $branches
	) {
		$this->id       = $id;
		$this->title    = $title;
		$this->url      = $url;
		$this->stand    = $stand;
		$this->branches = $branches;
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
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @return array
	 */
	public function getStand(): array {
		return $this->stand;
	}

	/**
	 * @return array
	 */
	public function getBranches(): array {
		return $this->branches;
	}
}

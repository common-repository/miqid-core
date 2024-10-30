<?php

namespace MIQID\Plugin\Core\Classes;

class Endpoint extends Base {
	/** @var string */
	private $Host;
	/** @var string|null */
	private $Version;

	public function __construct( ?array $Endpoint = null ) {
		if ( is_array( $Endpoint ) ) {
			$this->parse_array( $Endpoint );
		}
	}

	/**
	 * @param string $Host
	 *
	 * @return self
	 */
	public function set_host( string $Host ): self {
		$this->Host = $Host;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_host(): string {
		return $this->Host;
	}

	/**
	 * @param string|null $Version
	 *
	 * @return self
	 */
	public function set_version( ?string $Version ): self {
		$this->Version = $Version;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_version(): ?string {
		return $this->Version;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
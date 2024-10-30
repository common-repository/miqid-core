<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

class Profile extends \MIQID\Plugin\Core\Classes\DTO\Profile {
	/** @var string|null */
	private $Id;

	public function __construct( ?array $Profile = null ) {
		parent::__construct( $Profile );
		if ( is_array( $Profile ) ) {
			$this->parse_array( $Profile );
		}
	}

	/**
	 * @param string|null $Id
	 */
	public function set_id( ?string $Id ): void {
		$this->Id = $Id;
	}

	/**
	 * @return string|null
	 */
	public function get_id(): ?string {
		return $this->Id;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
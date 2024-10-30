<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use DateTime;
use MIQID\Plugin\Core\Classes\DTO\Address;

class UserAddress extends Address {
	/** @var DateTime|null */
	private $Created;
	/** @var DateTime|null */
	private $Changed;
	/** @var string|null */
	private $ChangedBy;

	public function __construct( array $UserAddress = null ) {
		parent::__construct( $UserAddress );
		if ( is_array( $UserAddress ) ) {
			$this->parse_array( $UserAddress );
		}
	}

	/**
	 * @param DateTime|string|null $Created
	 */
	public function set_created( $Created ): void {
		if ( is_string( $Created ) ) {
			$Created = date_create( $Created );
		}
		$this->Created = $Created;
	}

	/**
	 * @param null $format
	 *
	 * @return DateTime|string|null
	 */
	public function get_created( $format = null ) {
		if ( $this->Created instanceof DateTime && ! empty( $format ) ) {
			return $this->Created->format( $format );
		}

		return $this->Created;
	}

	/**
	 * @param DateTime|string|null $Changed
	 */
	public function set_changed( $Changed ): void {
		if ( is_string( $Changed ) ) {
			$Changed = date_create( $Changed );
		}
		$this->Changed = $Changed;
	}

	/**
	 * @param string|null $format
	 *
	 * @return DateTime|string|null
	 */
	public function get_changed( ?string $format = null ) {
		if ( $this->Changed instanceof DateTime && ! empty( $format ) ) {
			return $this->Changed->format( $format );
		}

		return $this->Changed;
	}

	/**
	 * @param string|null $ChangedBy
	 */
	public function set_changed_by( ?string $ChangedBy ): void {
		$this->ChangedBy = $ChangedBy;
	}

	/**
	 * @return string|null
	 */
	public function get_changed_by(): ?string {
		return $this->ChangedBy;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		$vars = array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);

		$vars['Created'] = $this->get_created( 'c' );
		$vars['Changed'] = $this->get_created( 'c' );

		return $vars;
	}

}
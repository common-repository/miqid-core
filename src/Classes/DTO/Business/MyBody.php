<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use DateTime;

class MyBody extends \MIQID\Plugin\Core\Classes\DTO\MyBody {
	/** @var string|null */
	private $Id;
	/** @var DateTime|null */
	private $Created;
	/** @var DateTime|null */
	private $Changed;
	/** @var string|null */
	private $ChangedBy;

	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct( ?array $MyBody = null ) {
		if ( is_array( $MyBody ) ) {
			$this->parse_array( $MyBody );
		}
	}

	/**
	 * @param string|null $Id
	 *
	 * @return self
	 */
	public function set_id( ?string $Id ): self {
		$this->Id = $Id;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_id(): ?string {
		return $this->Id;
	}

	/**
	 * @param DateTime|string|null $Created
	 *
	 * @return self
	 */
	public function set_created( $Created ): self {
		if ( is_string( $Created ) ) {
			$Created = date_create( $Created );
		}
		$this->Created = $Created;

		return $this;
	}

	public function get_created( ?string $format = null ) {
		if ( $this->Created instanceof DateTime && ! empty( $format ) ) {
			return $this->Created->format( $format );
		}

		return $this->Created;
	}

	/**
	 * @param DateTime|string|null $Changed
	 *
	 * @return self
	 */
	public function set_changed( $Changed ): self {
		if ( is_string( $Changed ) ) {
			$Changed = date_create( $Changed );
		}
		$this->Changed = $Changed;

		return $this;
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
	 *
	 * @return self
	 */
	public function set_changed_by( ?string $ChangedBy ): self {
		$this->ChangedBy = $ChangedBy;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_changed_by(): ?string {
		return $this->ChangedBy;
	}

	public function jsonSerialize(): array {
		$vars = array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);

		$vars['Created'] = $this->get_created( 'c' );
		$vars['Changed'] = $this->get_changed( 'c' );

		return $vars;
	}
}
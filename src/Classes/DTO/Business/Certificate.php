<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use DateTime;
use MIQID\Plugin\Core\Classes\DTO\Base;

class Certificate extends Base {
	/** @var bool */
	private $SsnValidated;
	/** @var string|null */
	private $TypeDoc;
	/** @var DateTime|null */
	private $DateOfIssue;
	/** @var DateTime|null */
	private $DateOfExpiry;
	/** @var string|null */
	private $GivenNames;
	/** @var string|null */
	private $Surname;

	/**
	 * @param bool|int|string|null $SsnValidated
	 *
	 * @return self
	 */
	public function set_ssn_validated( $SsnValidated ): self {
		if ( ! is_null( $SsnValidated ) ) {
			$SsnValidated = filter_var( $SsnValidated, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		}
		$this->SsnValidated = $SsnValidated;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_ssn_validated(): bool {
		return $this->SsnValidated ?? false;
	}

	/**
	 * @param string|null $TypeDoc
	 *
	 * @return self
	 */
	public function set_type_doc( ?string $TypeDoc ): self {
		$this->TypeDoc = $TypeDoc;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_type_doc(): ?string {
		return $this->TypeDoc;
	}

	/**
	 * @param DateTime|string|null $DateOfIssue
	 *
	 * @return self
	 */
	public function set_date_of_issue( $DateOfIssue ): self {
		if ( is_string( $DateOfIssue ) ) {
			$DateOfIssue = date_create( $DateOfIssue );
		}
		$this->DateOfIssue = $DateOfIssue;

		return $this;
	}

	/**
	 * @param string|null $format
	 *
	 * @return DateTime|string|null
	 */
	public function get_date_of_issue( ?string $format = null ) {
		if ( $this->DateOfIssue instanceof DateTime && ! empty( $format ) ) {
			return $this->DateOfIssue->format( $format );
		}

		return $this->DateOfIssue;
	}

	/**
	 * @param DateTime|string|null $DateOfExpiry
	 *
	 * @return self
	 */
	public function set_date_of_expiry( $DateOfExpiry ): self {
		if ( is_string( $DateOfExpiry ) ) {
			$DateOfExpiry = date_create( $DateOfExpiry );
		}
		$this->DateOfExpiry = $DateOfExpiry;

		return $this;
	}

	/**
	 * @param string|null $format
	 *
	 * @return DateTime|string|null
	 */
	public function get_date_of_expiry( ?string $format = null ) {
		if ( $this->DateOfExpiry instanceof DateTime && ! empty( $format ) ) {
			return $this->DateOfExpiry->format( $format );
		}

		return $this->DateOfExpiry;
	}

	/**
	 * @param string|null $GivenNames
	 *
	 * @return self
	 */
	public function set_given_names( ?string $GivenNames ): self {
		$this->GivenNames = $GivenNames;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_given_names(): ?string {
		return $this->GivenNames;
	}

	/**
	 * @param string|null $Surname
	 *
	 * @return self
	 */
	public function set_surname( ?string $Surname ): self {
		$this->Surname = $Surname;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_surname(): ?string {
		return $this->Surname;
	}

	public function jsonSerialize(): array {
		$vars                 = get_object_vars( $this );
		$vars['DateOfIssue']  = $this->get_date_of_issue( 'c' );
		$vars['DateOfExpiry'] = $this->get_date_of_expiry( 'c' );

		return $vars;
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use DateTime;

class Passport extends Base {
	/** @var bool */
	private $SsnValidated;
	/** @var string|null */
	private $TypeDoc;
	/** @var string|null */
	private $Authority;
	/** @var string|null */
	private $BarcodeLine1;
	/** @var string|null */
	private $BarcodeLine2;
	/** @var string|null */
	private $Code;
	/** @var DateTime|null */
	private $DateOfBirth;
	/** @var DateTime|null */
	private $DateOfExpiry;
	/** @var DateTime|null */
	private $DateOfIssue;
	/** @var string|null */
	private $PlaceOfBirth;
	/** @var string|null */
	private $GivenNames;
	/** @var string|null */
	private $Surname;
	/** @var string|null */
	private $Height;
	/** @var string|null */
	private $Nationality;
	/** @var string|null */
	private $PassportNumber;

	/**
	 * Passport constructor.
	 *
	 * @param array|null $Passport
	 */
	public function __construct( ?array $Passport = null ) {
		if ( is_array( $Passport ) ) {
			$this->parse_array( $Passport );
		}
	}

	/**
	 * @param bool|int|string|null $SsnValidated
	 *
	 * @return self
	 */
	public function set_ssn_validated( $SsnValidated ): self {
		if ( ! is_null( $SsnValidated ) ) {
			$SsnValidated = filter_var( $SsnValidated, FILTER_VALIDATE_BOOLEAN );
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
	 * @param string|null $Authority
	 *
	 * @return self
	 */
	public function set_authority( ?string $Authority ): self {
		$this->Authority = $Authority;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_authority(): ?string {
		return $this->Authority;
	}

	/**
	 * @param string|null $BarcodeLine1
	 *
	 * @return self
	 */
	public function set_barcode_line_1( ?string $BarcodeLine1 ): self {
		$this->BarcodeLine1 = $BarcodeLine1;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_barcode_line_1(): ?string {
		return $this->BarcodeLine1;
	}

	/**
	 * @param string|null $BarcodeLine2
	 *
	 * @return self
	 */
	public function set_barcode_line_2( ?string $BarcodeLine2 ): self {
		$this->BarcodeLine2 = $BarcodeLine2;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_barcode_line_2(): ?string {
		return $this->BarcodeLine2;
	}

	/**
	 * @param string|null $Code
	 *
	 * @return self
	 */
	public function set_code( ?string $Code ): self {
		$this->Code = $Code;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_code(): ?string {
		return $this->Code;
	}

	/**
	 * @param DateTime|string|null $DateOfBirth
	 *
	 * @return self
	 */
	public function set_date_of_birth( $DateOfBirth ): self {
		if ( is_string( $DateOfBirth ) ) {
			$DateOfBirth = date_create( $DateOfBirth );
		}
		$this->DateOfBirth = $DateOfBirth;

		return $this;

	}

	/**
	 * @param string|null $format
	 *
	 * @return DateTime|string|null
	 */
	public function get_date_of_birth( ?string $format = null ) {
		if ( $this->DateOfBirth instanceof DateTime && ! empty( $format ) ) {
			return $this->DateOfBirth->format( $format );
		}

		return $this->DateOfBirth;
	}

	/**
	 * @param string|null $DateOfExpiry
	 *
	 * @return self
	 */
	public function set_date_of_expiry( ?string $DateOfExpiry ): self {
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
	 * @param string|null $DateOfIssue
	 *
	 * @return self
	 */
	public function set_date_of_issue( ?string $DateOfIssue ): self {
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
	 * @param string|null $PlaceOfBirth
	 *
	 * @return self
	 */
	public function set_place_of_birth( ?string $PlaceOfBirth ): self {
		$this->PlaceOfBirth = $PlaceOfBirth;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_place_of_birth(): ?string {
		return $this->PlaceOfBirth;
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

	/**
	 * @param string|null $Height
	 *
	 * @return self
	 */
	public function set_height( ?string $Height ): self {
		$this->Height = $Height;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_height(): ?string {
		return $this->Height;
	}

	/**
	 * @param string|null $Nationality
	 *
	 * @return self
	 */
	public function set_nationality( ?string $Nationality ): self {
		$this->Nationality = $Nationality;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_nationality(): ?string {
		return $this->Nationality;
	}

	/**
	 * @param string|null $PassportNumber
	 *
	 * @return self
	 */
	public function set_passport_number( ?string $PassportNumber ): self {
		$this->PassportNumber = $PassportNumber;

		return $this;

	}

	/**
	 * @return string|null
	 */
	public function get_passport_number(): ?string {
		return $this->PassportNumber;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
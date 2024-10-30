<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use DateTime;

class DriversLicense extends Base {
	/** @var bool */
	private $SsnValidated;
	/** @var string|null */
	private $TypeDoc;
	/** @var string|null */
	private $Surname;
	/** @var string|null */
	private $GivenNames;
	/** @var string|null */
	private $DateOfBirthPlaceOfBirth;
	/** @var DateTime|null */
	private $DateOfIssue;
	/** @var string|null */
	private $IssuingAuthority;
	/** @var DateTime|null */
	private $DateOfExpiry;
	/** @var string|null */
	private $LicenseNumber;
	/** @var string|null */
	private $LicenseCategories;

	public function __construct( ?array $DriversLicense = [] ) {
		if ( is_array( $DriversLicense ) ) {
			$this->parse_array( $DriversLicense );
		}
	}

	/**
	 * @param bool|int|string|null $SsnValidated
	 */
	public function set_ssn_validated( $SsnValidated ): void {
		if ( ! is_null( $SsnValidated ) ) {
			$SsnValidated = filter_var( $SsnValidated, FILTER_VALIDATE_BOOLEAN );
		}
		$this->SsnValidated = $SsnValidated;
	}

	/**
	 * @return bool
	 */
	public function is_ssn_validated(): bool {
		return $this->SsnValidated ?? false;
	}

	/**
	 * @param string|null $TypeDoc
	 */
	public function set_type_doc( ?string $TypeDoc ): void {
		$this->TypeDoc = $TypeDoc;
	}

	/**
	 * @return string|null
	 */
	public function get_type_doc(): ?string {
		return $this->TypeDoc;
	}

	/**
	 * @param string|null $Surname
	 */
	public function set_surname( ?string $Surname ): void {
		$this->Surname = $Surname;
	}

	/**
	 * @return string|null
	 */
	public function get_surname(): ?string {
		return $this->Surname;
	}

	/**
	 * @param string|null $GivenNames
	 */
	public function set_given_names( ?string $GivenNames ): void {
		$this->GivenNames = $GivenNames;
	}

	/**
	 * @return string|null
	 */
	public function get_given_names(): ?string {
		return $this->GivenNames;
	}

	/**
	 * @param string|null $DateOfBirthPlaceOfBirth
	 */
	public function set_date_of_birth_place_of_birth( ?string $DateOfBirthPlaceOfBirth ): void {
		$this->DateOfBirthPlaceOfBirth = $DateOfBirthPlaceOfBirth;
	}

	/**
	 * @return string|null
	 */
	public function get_date_of_birth_place_of_birth(): ?string {
		return $this->DateOfBirthPlaceOfBirth;
	}

	/**
	 * @param DateTime|string|null $DateOfIssue
	 */
	public function set_date_of_issue( $DateOfIssue ): void {
		if ( is_string( $DateOfIssue ) ) {
			$DateOfIssue = date_create( $DateOfIssue );
		}
		$this->DateOfIssue = $DateOfIssue;
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
	 * @param string|null $IssuingAuthority
	 */
	public function set_issuing_authority( ?string $IssuingAuthority ): void {
		$this->IssuingAuthority = $IssuingAuthority;
	}

	/**
	 * @return string|null
	 */
	public function get_issuing_authority(): ?string {
		return $this->IssuingAuthority;
	}

	/**
	 * @param DateTime|string|null $DateOfExpiry
	 */
	public function set_date_of_expiry( $DateOfExpiry ): void {
		if ( is_string( $DateOfExpiry ) ) {
			$DateOfExpiry = date_create( $DateOfExpiry );
		}
		$this->DateOfExpiry = $DateOfExpiry;
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
	 * @param string|null $LicenseNumber
	 */
	public function set_license_number( ?string $LicenseNumber ): void {
		$this->LicenseNumber = $LicenseNumber;
	}

	/**
	 * @return string|null
	 */
	public function get_license_number(): ?string {
		return $this->LicenseNumber;
	}

	/**
	 * @param string|null $LicenseCategories
	 */
	public function set_license_categories( ?string $LicenseCategories ): void {
		$this->LicenseCategories = $LicenseCategories;
	}

	/**
	 * @return string|null
	 */
	public function get_license_categories(): ?string {
		return $this->LicenseCategories;
	}

	public function jsonSerialize(): array {
		$vars                 = get_object_vars( $this );
		$vars['DateOfIssue']  = $this->get_date_of_issue( 'c' );
		$vars['DateOfExpiry'] = $this->get_date_of_expiry( 'c' );

		return $vars;
	}
}
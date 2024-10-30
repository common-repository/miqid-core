<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Base;

class Kyc extends Base {
	/** @var string|null */
	private $LegalName;
	/** @var string|null */
	private $FirstName;
	/** @var string|null */
	private $LastName;
	/** @var bool */
	private $ProfileVerified;
	/** @var string|null */
	private $PassportNumber;
	/** @var string|null */
	private $PassportImage;
	/** @var string|null */
	private $DriversLicenseImage;
	/** @var UserAddress */
	private $Address;

	public function __construct( ?array $Kyc = null ) {
		if ( is_array( $Kyc ) ) {
			$this->parse_array( $Kyc );
		}
	}

	/**
	 * @param string|null $LegalName
	 */
	public function set_legal_name( ?string $LegalName ): void {
		$this->LegalName = $LegalName;
	}

	/**
	 * @return string|null
	 */
	public function get_legal_name(): ?string {
		return $this->LegalName;
	}

	/**
	 * @param string|null $FirstName
	 */
	public function set_first_name( ?string $FirstName ): void {
		$this->FirstName = $FirstName;
	}

	/**
	 * @return string|null
	 */
	public function get_first_name(): ?string {
		return $this->FirstName;
	}

	/**
	 * @param string|null $LastName
	 */
	public function set_last_name( ?string $LastName ): void {
		$this->LastName = $LastName;
	}

	/**
	 * @return string|null
	 */
	public function get_last_name(): ?string {
		return $this->LastName;
	}

	/**
	 * @param bool $ProfileVerified
	 */
	public function set_profile_verified( bool $ProfileVerified ): void {
		$this->ProfileVerified = $ProfileVerified;
	}

	/**
	 * @return bool
	 */
	public function is_profile_verified(): bool {
		return $this->ProfileVerified;
	}

	/**
	 * @param string|null $PassportNumber
	 */
	public function set_passport_number( ?string $PassportNumber ): void {
		$this->PassportNumber = $PassportNumber;
	}

	/**
	 * @return string|null
	 */
	public function get_passport_number(): ?string {
		return $this->PassportNumber;
	}

	/**
	 * @param string|null $PassportImage
	 */
	public function set_passport_image( ?string $PassportImage ): void {
		$this->PassportImage = $PassportImage;
	}

	/**
	 * @return string|null
	 */
	public function get_passport_image(): ?string {
		return $this->PassportImage;
	}

	/**
	 * @param string|null $DriversLicenseImage
	 */
	public function set_drivers_license_image( ?string $DriversLicenseImage ): void {
		$this->DriversLicenseImage = $DriversLicenseImage;
	}

	/**
	 * @return string|null
	 */
	public function get_drivers_license_image(): ?string {
		return $this->DriversLicenseImage;
	}

	/**
	 * @param UserAddress|array|null $Address
	 */
	public function set_address( $Address ): void {
		if ( is_array( $Address ) ) {
			$Address = new UserAddress( $Address );
		}
		$this->Address = $Address;
	}

	/**
	 * @return UserAddress
	 */
	public function get_address(): UserAddress {
		return $this->Address ?? new UserAddress();
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Base;
use MIQID\Plugin\Core\Classes\DTO\FileContentResult;

class Showcase extends Base {
	/** @var Profile */
	private $Profile;
	/** @var UserAddress[] */
	private $Addresses;
	/** @var MyBody */
	private $MyBody;
	/** @var string|null */
	private $DriversLicenseNumber;
	/** @var string|null */
	private $PassportNumber;
	/** @var string|null */
	private $SSN;
	/** @var FileContentResult */
	private $PassportFaceImage;
	/** @var UserSettings */
	private $Settings;

	public function __construct( ?array $Showcase = null ) {
		if ( is_array( $Showcase ) ) {
			$this->parse_array( $Showcase );
		}
	}

	/**
	 * @param Profile|array|null $Profile
	 *
	 * @return self
	 */
	public function set_profile( $Profile ): self {
		if ( is_array( $Profile ) ) {
			$Profile = new Profile( $Profile );
		}
		$this->Profile = $Profile;

		return $this;
	}

	/**
	 * @return Profile
	 */
	public function get_profile(): Profile {
		return $this->Profile;
	}

	/**
	 * @param UserAddress[] $Addresses
	 *
	 * @return self
	 */
	public function set_addresses( array $Addresses ): self {
		if ( is_array( $Addresses ) ) {
			foreach ( $Addresses as $key => $address ) {
				if ( is_array( $address ) ) {
					$Addresses[ $key ] = new UserAddress( $address );
				}
			}
		}
		$this->Addresses = $Addresses;

		return $this;
	}

	/**
	 * @return UserAddress[]
	 */
	public function get_addresses(): array {
		return $this->Addresses;
	}

	/**
	 * @param MyBody|array|null $MyBody
	 *
	 * @return self
	 */
	public function set_my_body( $MyBody ): self {
		if ( is_array( $MyBody ) ) {
			$MyBody = new MyBody( $MyBody );
		}
		$this->MyBody = $MyBody;

		return $this;
	}

	/**
	 * @return MyBody
	 */
	public function get_my_body(): MyBody {
		return $this->MyBody;
	}

	/**
	 * @param string|null $DriversLicenseNumber
	 *
	 * @return self
	 */
	public function set_drivers_license_number( ?string $DriversLicenseNumber ): self {
		$this->DriversLicenseNumber = $DriversLicenseNumber;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_drivers_license_number(): ?string {
		return $this->DriversLicenseNumber;
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

	/**
	 * @param string|null $SSN
	 *
	 * @return self
	 */
	public function set_SSN( ?string $SSN ): self {
		$this->SSN = $SSN;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_SSN(): ?string {
		return $this->SSN;
	}

	/**
	 * @param FileContentResult|array|null $PassportFaceImage
	 *
	 * @return self
	 */
	public function set_passport_face_image( $PassportFaceImage ): self {
		if ( is_array( $PassportFaceImage ) ) {
			$PassportFaceImage = new FileContentResult( $PassportFaceImage );
		}
		$this->PassportFaceImage = $PassportFaceImage;

		return $this;
	}

	/**
	 * @return FileContentResult
	 */
	public function get_passport_face_image(): FileContentResult {
		return $this->PassportFaceImage;
	}

	/**
	 * @param UserSettings|array|null $Settings
	 *
	 * @return self
	 */
	public function set_settings( $Settings ): self {
		if ( is_array( $Settings ) ) {
			$Settings = new UserSettings( $Settings );
		}
		$this->Settings = $Settings;

		return $this;
	}

	/**
	 * @return UserSettings
	 */
	public function get_settings(): UserSettings {
		return $this->Settings ?? new UserSettings();
	}


	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class CompanyRole extends Base {
	const ProfileData     = 1;
	const BodyMeasurement = 101;
	const Shopping        = 201;
	const LoyaltyClub     = 301;
	const Certificate     = 401;
	const AddressData     = 501;

	/** @var int */
	private $role;
	/** @var string */
	private $companyTypeId;

	public function __construct( $role = null, $companyTypeId = null ) {
		$this->set_role( $role );
		$this->set_company_type_id( $companyTypeId );
	}

	/**
	 * @param array|int|null $role
	 */
	public function set_role( $role ): void {
		if ( is_array( $role ) ) {
			$this->parse_array( $role );
		} else {
			if ( isset( $role ) && ! in_array( $role, [
					self::ProfileData,
					self::BodyMeasurement,
					self::Shopping,
					self::LoyaltyClub,
					self::Certificate,
					self::AddressData,
				] ) ) {
				$role = null;
			}

			$this->role = $role ?? $this->role;
		}
	}

	/**
	 * @param string|null $companyTypeId
	 */
	public function set_company_type_id( ?string $companyTypeId ): void {
		$this->companyTypeId = $companyTypeId ?? $this->companyTypeId;
	}

	/**
	 * @return int
	 */
	public function get_role(): int {
		return $this->role ?? self::ProfileData;
	}

	/**
	 * @return string
	 */
	public function get_company_type_id(): string {
		return $this->companyTypeId ?? '';
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
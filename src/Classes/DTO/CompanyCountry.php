<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class CompanyCountry extends Company {
	/** @var string */
	private $companyCountryName;
	/** @var string */
	private $country;
	/** @var string */
	private $companyId;

	public function __construct(
		$companyCountryName = null,
		$companyName = null,
		$phoneNumber = null,
		$email = null,
		$vatNr = null,
		$vatNrValidated = null,
		$country = null,
		$companyId = null,
		$companyType = null
	) {
		parent::__construct( $companyName, $phoneNumber, $email, $vatNr, $vatNrValidated, $companyType );
		$this->set_company_country_name( $companyCountryName );
		$this->set_country( $country );
		$this->set_company_id( $companyId );
	}

	/**
	 * @param array|string|null $companyCountryName
	 */
	public function set_company_country_name( $companyCountryName ): void {
		if ( is_array( $companyCountryName ) ) {
			$this->parse_array( $companyCountryName );
		} else {
			$this->companyCountryName = $companyCountryName ?? $this->companyCountryName;
		}
	}

	/**
	 * @param string|null $country
	 */
	public function set_country( ?string $country ): void {
		$this->country = $country ?? $this->country;
	}

	/**
	 * @param string|null $companyId
	 */
	public function set_company_id( ?string $companyId ): void {
		$this->companyId = $companyId ?? $this->companyId;
	}

	/**
	 * @return string
	 */
	public function get_company_country_name(): string {
		return $this->companyCountryName ?? '';
	}

	/**
	 * @return string
	 */
	public function get_country(): string {
		return $this->country ?? '';
	}

	/**
	 * @return string
	 */
	public function get_company_id(): string {
		return $this->companyId ?? '';
	}

	public function jsonSerialize(): array {
		$arr = array_merge(
			get_object_vars( $this ),
			parent::jsonSerialize()
		);

		unset( $arr['companyName'] );

		return $arr;
	}
}
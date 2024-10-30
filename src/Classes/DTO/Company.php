<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class Company extends Base {
	/** @var string */
	private $companyName;
	/** @var string */
	private $phoneNumber;
	/** @var string */
	private $email;
	/** @var string */
	private $vatNr;
	/** @var bool */
	private $vatNrValidated;
	/** @var CompanyType */
	public $companyType;

	public function __construct( $companyName = null, $phoneNumber = null, $email = null, $vatNr = null, $vatNrValidated = null, $companyType = null ) {
		$this->set_company_name( $companyName );
		$this->set_phone_number( $phoneNumber );
		$this->set_email( $email );
		$this->set_vat_nr( $vatNr );
		$this->set_vat_nr_validated( $vatNrValidated );
		$this->set_company_type( $companyType );
	}

	/**
	 * @param array|string|null $companyName
	 */
	public function set_company_name( $companyName ): void {
		if ( is_array( $companyName ) ) {
			$this->parse_array( $companyName );
		} else {
			$this->companyName = $companyName ?? $this->companyName;
		}
	}

	/**
	 * @param string|null $phoneNumber
	 */
	public function set_phone_number( ?string $phoneNumber ): void {
		$this->phoneNumber = $phoneNumber ?? $this->phoneNumber;
	}

	/**
	 * @param string|null $email
	 */
	public function set_email( ?string $email ): void {
		$this->email = isset( $email ) ? filter_var( $email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE ) : $this->email;
	}

	/**
	 * @param string|null $vatNr
	 */
	public function set_vat_nr( ?string $vatNr ): void {
		$this->vatNr = $vatNr ?? $this->vatNr;
	}

	/**
	 * @param bool|string|int|null $vatNrValidated
	 */
	public function set_vat_nr_validated( $vatNrValidated ): void {
		$this->vatNrValidated = isset( $vatNrValidated ) ? filter_var( $vatNrValidated, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) : $this->vatNrValidated;
	}

	/**
	 * @param array|CompanyType|null $companyType
	 */
	public function set_company_type( $companyType ): void {
		if ( is_array( $companyType ) ) {
			$companyType = new CompanyType( $companyType );
		}
		$this->companyType = $companyType ?? $this->companyType;
	}

	/**
	 * @return string
	 */
	public function get_company_name(): string {
		return $this->companyName ?? '';
	}

	/**
	 * @return string
	 */
	public function get_phone_number(): string {
		return $this->phoneNumber ?? '';
	}

	/**
	 * @return string
	 */
	public function get_email(): string {
		return $this->email ?? '';
	}

	/**
	 * @return string
	 */
	public function get_vat_nr(): string {
		return $this->vatNr ?? '';
	}

	/**
	 * @return bool
	 */
	public function is_vat_nr_validated(): bool {
		return $this->vatNrValidated ?? false;
	}

	/**
	 * @return CompanyType
	 */
	public function get_company_type(): CompanyType {
		return $this->companyType ?? new CompanyType();
	}

	public function jsonSerialize(): array {
		$arr                = get_object_vars( $this );
		$arr['companyType'] = $this->get_company_type();

		return $arr;
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class HealthInsuranceCard extends Base {
	/** @var bool */
	private $SsnValidated;
	/** @var string|null */
	private $TypeDoc;
	/** @var string|null */
	private $PractitionerLine1;
	/** @var string|null */
	private $PractitionerLine2;
	/** @var string|null */
	private $PractitionerLine3;
	/** @var string|null */
	private $PractitionerTlf;
	/** @var string|null */
	private $Sikr;
	/** @var string|null */
	private $ValidFrom;
	/** @var string|null */
	private $NameAddressLine1;
	/** @var string|null */
	private $NameAddressLine2;
	/** @var string|null */
	private $NameAddressLine3;
	/** @var string|null */
	private $NameAddressLine4;

	public function __construct( ?array $HealthInsuranceCard = [] ) {
		if ( is_array( $HealthInsuranceCard ) ) {
			$this->parse_array( $HealthInsuranceCard );
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
	 * @param string|null $PractitionerLine1
	 */
	public function set_practitioner_line_1( ?string $PractitionerLine1 ): void {
		$this->PractitionerLine1 = $PractitionerLine1;
	}

	/**
	 * @return string|null
	 */
	public function get_practitioner_line_1(): ?string {
		return $this->PractitionerLine1;
	}

	/**
	 * @param string|null $PractitionerLine2
	 */
	public function set_practitioner_line_2( ?string $PractitionerLine2 ): void {
		$this->PractitionerLine2 = $PractitionerLine2;
	}

	/**
	 * @return string|null
	 */
	public function get_practitioner_line_2(): ?string {
		return $this->PractitionerLine2;
	}

	/**
	 * @param string|null $PractitionerLine3
	 */
	public function set_practitioner_line_3( ?string $PractitionerLine3 ): void {
		$this->PractitionerLine3 = $PractitionerLine3;
	}

	/**
	 * @return string|null
	 */
	public function get_practitioner_line_3(): ?string {
		return $this->PractitionerLine3;
	}

	/**
	 * @param string|null $PractitionerTlf
	 */
	public function set_practitioner_tlf( ?string $PractitionerTlf ): void {
		$this->PractitionerTlf = $PractitionerTlf;
	}

	/**
	 * @return string|null
	 */
	public function get_practitioner_tlf(): ?string {
		return $this->PractitionerTlf;
	}

	/**
	 * @param string|null $Sikr
	 */
	public function set_sikr( ?string $Sikr ): void {
		$this->Sikr = $Sikr;
	}

	/**
	 * @return string|null
	 */
	public function get_sikr(): ?string {
		return $this->Sikr;
	}

	/**
	 * @param string|null $ValidFrom
	 */
	public function set_valid_from( ?string $ValidFrom ): void {
		$this->ValidFrom = $ValidFrom;
	}

	/**
	 * @return string|null
	 */
	public function get_valid_from(): ?string {
		return $this->ValidFrom;
	}

	/**
	 * @param string|null $NameAddressLine1
	 */
	public function set_name_address_line_1( ?string $NameAddressLine1 ): void {
		$this->NameAddressLine1 = $NameAddressLine1;
	}

	/**
	 * @return string|null
	 */
	public function get_name_address_line_1(): ?string {
		return $this->NameAddressLine1;
	}

	/**
	 * @param string|null $NameAddressLine2
	 */
	public function set_name_address_line_2( ?string $NameAddressLine2 ): void {
		$this->NameAddressLine2 = $NameAddressLine2;
	}

	/**
	 * @return string|null
	 */
	public function get_name_address_line_2(): ?string {
		return $this->NameAddressLine2;
	}

	/**
	 * @param string|null $NameAddressLine3
	 */
	public function set_name_address_line_3( ?string $NameAddressLine3 ): void {
		$this->NameAddressLine3 = $NameAddressLine3;
	}

	/**
	 * @return string|null
	 */
	public function get_name_address_line_3(): ?string {
		return $this->NameAddressLine3;
	}

	/**
	 * @param string|null $NameAddressLine4
	 */
	public function set_name_address_line_4( ?string $NameAddressLine4 ): void {
		$this->NameAddressLine4 = $NameAddressLine4;
	}

	/**
	 * @return string|null
	 */
	public function get_name_address_line_4(): ?string {
		return $this->NameAddressLine4;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
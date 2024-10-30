<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use DateTime;
use MIQID\Plugin\Core\Classes\DTO\Enum\SexType;

class Profile extends Base {
	/** @var string|null */
	private $Email;
	/** @var string|null */
	private $FirstName;
	/** @var string|null */
	private $LastName;
	/** @var string|null */
	private $LegalName;
	/** @var DateTime|null */
	private $DateOfBirth;
	/** @var string|null */
	private $CprNumber;
	/** @var SexType|null */
	private $SexType;
	/** @var string|null */
	private $PhoneNumber;
	/** @var string|null */
	private $Nationality;
	/** @var string|null */
	private $ContractbookDocumentId;
	/** @var bool */
	private $Verified;
	/** @var int|null */
	private $Age;

	public function __construct( ?array $Profile = null ) {
		$this->SexType = new SexType( SexType::NotSet );
		if ( is_array( $Profile ) ) {
			$this->parse_array( $Profile );
		}
		__( 'Profile', 'miqid-core' );
		__( 'Email', 'miqid-core' );
		__( 'FirstName', 'miqid-core' );
		__( 'LastName', 'miqid-core' );
		__( 'LegalName', 'miqid-core' );
		__( 'DateOfBirth', 'miqid-core' );
		__( 'CprNumber', 'miqid-core' );
		__( 'SexType', 'miqid-core' );
		__( 'PhoneNumber', 'miqid-core' );
		__( 'Nationality', 'miqid-core' );
		__( 'Verified', 'miqid-core' );
		__( 'Age', 'miqid-core' );
	}

	/**
	 * @param string|null $Email
	 *
	 * @return self
	 */
	public function set_email( ?string $Email ): self {
		$this->Email = $Email;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_email(): ?string {
		return $this->Email;
	}

	/**
	 * @param string|null $FirstName
	 *
	 * @return self
	 */
	public function set_first_name( ?string $FirstName ): self {
		$this->FirstName = $FirstName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_first_name(): ?string {
		return $this->FirstName;
	}

	/**
	 * @param string|null $LastName
	 *
	 * @return self
	 */
	public function set_last_name( ?string $LastName ): self {
		$this->LastName = $LastName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_last_name(): ?string {
		return $this->LastName;
	}

	/**
	 * @param string|null $LegalName
	 *
	 * @return self
	 */
	public function set_legal_name( ?string $LegalName ): self {
		$this->LegalName = $LegalName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_legal_name(): ?string {
		return $this->LegalName;
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

		if ( $DateOfBirth instanceof DateTime ) {
			$this->set_cpr_number( $this->get_date_of_birth( 'dmy\-XXXX' ) );
			$this->set_age( $this->get_date_of_birth() );
		}

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
	 * @param string|null $CprNumber
	 *
	 * @return self
	 */
	public function set_cpr_number( ?string $CprNumber ): self {
		$this->CprNumber = $CprNumber;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_cpr_number(): ?string {
		return $this->CprNumber;
	}

	/**
	 * @param SexType|int|string|null $SexType
	 *
	 * @return self
	 */
	public function set_sex_type( $SexType ): self {
		if ( is_string( $SexType ) ) {
			$SexType = SexType::$SexType();
		}
		if ( empty( $SexType ) ) {
			$SexType = SexType::NotSet;
		}
		$this->SexType = new SexType( $SexType );

		return $this;
	}

	/**
	 * @return SexType|null
	 */
	public function get_sex_type(): ?SexType {
		return $this->SexType;
	}

	/**
	 * @param string|null $PhoneNumber
	 *
	 * @return self
	 */
	public function set_phone_number( ?string $PhoneNumber ): self {
		$this->PhoneNumber = $PhoneNumber;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_phone_number(): ?string {
		return $this->PhoneNumber;
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
	 * @param string|null $ContractbookDocumentId
	 *
	 * @return self
	 */
	public function set_contractbook_document_id( ?string $ContractbookDocumentId ): self {
		$this->ContractbookDocumentId = $ContractbookDocumentId;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_contractbook_document_id(): ?string {
		return $this->ContractbookDocumentId;
	}

	/**
	 * @param bool|int|string|null $Verified
	 *
	 * @return self
	 */
	public function set_verified( $Verified ): self {
		if ( ! is_null( $Verified ) ) {
			$Verified = filter_var( $Verified, FILTER_VALIDATE_BOOLEAN );
		}
		$this->Verified = $Verified;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_verified(): bool {
		return $this->Verified ?? false;
	}

	/**
	 * @param DateTime|int|null $Age
	 */
	public function set_age( $Age ): void {
		if ( $Age instanceof DateTime ) {
			$Age = ( new DateTime() )->diff( $Age )->y;
		}
		$this->Age = $Age;
	}

	/**
	 * @return int|null
	 */
	public function get_age(): ?int {
		return $this->Age;
	}

	function get_full_name(): string {
		return trim( sprintf( '%s %s', $this->get_first_name(), $this->get_last_name() ) );
	}

	public function jsonSerialize(): array {
		$vars                = get_object_vars( $this );
		$vars['DateOfBirth'] = $this->get_date_of_birth( 'c' );

		return $vars;
	}
}
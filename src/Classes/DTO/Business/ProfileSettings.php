<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Enum\PermissionType;

class ProfileSettings extends SettingsBase {
	/** @var PermissionType */
	private $FirstName;
	/** @var PermissionType */
	private $LastName;
	/** @var PermissionType */
	private $Email;
	/** @var PermissionType */
	private $Address;
	/** @var PermissionType */
	private $CprNumber;
	/** @var PermissionType */
	private $DateOfBirth;
	/** @var PermissionType */
	private $Nationality;
	/** @var PermissionType */
	private $PhoneNumber;
	/** @var PermissionType */
	private $SexType;
	/** @var PermissionType */
	private $ProfilePicture;

	public function __construct( ?array $ProfileSettings = null ) {
		if ( is_array( $ProfileSettings ) ) {
			$this->parse_array( $ProfileSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $FirstName
	 *
	 * @return self
	 */
	public function set_first_name( $FirstName ): self {
		if ( is_string( $FirstName ) ) {
			$FirstName = PermissionType::$FirstName();
		}
		$this->FirstName = new PermissionType( $FirstName );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_first_name(): PermissionType {
		return $this->FirstName;
	}

	/**
	 * @param PermissionType|int|string $LastName
	 *
	 * @return self
	 */
	public function set_last_name( $LastName ): self {
		if ( is_string( $LastName ) ) {
			$LastName = PermissionType::$LastName();
		}
		$this->LastName = new PermissionType( $LastName );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_last_name(): PermissionType {
		return $this->LastName;
	}

	/**
	 * @param PermissionType|int|string $Email
	 *
	 * @return self
	 */
	public function set_email( $Email ): self {
		if ( is_string( $Email ) ) {
			$Email = PermissionType::$Email();
		}
		$this->Email = new PermissionType( $Email );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_email(): PermissionType {
		return $this->Email;
	}

	/**
	 * @param PermissionType|int|string $Address
	 *
	 * @return self
	 */
	public function set_address( $Address ): self {
		if ( is_string( $Address ) ) {
			$Address = PermissionType::$Address();
		}
		$this->Address = new PermissionType( $Address );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_address(): PermissionType {
		return $this->Address;
	}

	/**
	 * @param PermissionType|int|string $CprNumber
	 *
	 * @return self
	 */
	public function set_cpr_number( $CprNumber ): self {
		if ( is_string( $CprNumber ) ) {
			$CprNumber = PermissionType::$CprNumber();
		}
		$this->CprNumber = new PermissionType( $CprNumber );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_cpr_number(): PermissionType {
		return $this->CprNumber;
	}

	/**
	 * @param PermissionType|int|string $DateOfBirth
	 *
	 * @return self
	 */
	public function set_date_of_birth( $DateOfBirth ): self {
		if ( is_string( $DateOfBirth ) ) {
			$DateOfBirth = PermissionType::$DateOfBirth();
		}
		$this->DateOfBirth = new PermissionType( $DateOfBirth );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_date_of_birth(): PermissionType {
		return $this->DateOfBirth;
	}

	/**
	 * @param PermissionType|int|string $Nationality
	 *
	 * @return self
	 */
	public function set_nationality( $Nationality ): self {
		if ( is_string( $Nationality ) ) {
			$Nationality = PermissionType::$Nationality();
		}
		$this->Nationality = new PermissionType( $Nationality );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_nationality(): PermissionType {
		return $this->Nationality;
	}

	/**
	 * @param PermissionType|int|string $PhoneNumber
	 *
	 * @return self
	 */
	public function set_phone_number( $PhoneNumber ): self {
		if ( is_string( $PhoneNumber ) ) {
			$PhoneNumber = PermissionType::$PhoneNumber();
		}
		$this->PhoneNumber = new PermissionType( $PhoneNumber );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_phone_number(): PermissionType {
		return $this->PhoneNumber;
	}

	/**
	 * @param PermissionType|int|string $SexType
	 *
	 * @return self
	 */
	public function set_sex_type( $SexType ): self {
		if ( is_string( $SexType ) ) {
			$SexType = PermissionType::$SexType();
		}
		$this->SexType = new PermissionType( $SexType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_sex_type(): PermissionType {
		return $this->SexType;
	}

	/**
	 * @param PermissionType|int|string $ProfilePicture
	 *
	 * @return self
	 */
	public function set_profile_picture( $ProfilePicture ): self {
		if ( is_string( $ProfilePicture ) ) {
			$ProfilePicture = PermissionType::$ProfilePicture();
		}
		$this->ProfilePicture = new PermissionType( $ProfilePicture );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_profile_picture(): PermissionType {
		return $this->ProfilePicture;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
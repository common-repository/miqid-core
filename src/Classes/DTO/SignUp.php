<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use DateTime;

class SignUp extends Profile {
	/** @var string */
	private $password;

	/**
	 * SignUp constructor.
	 *
	 * @param  array|string|null  $email
	 * @param  string|null  $password
	 * @param  string|null  $firstName
	 * @param  string|null  $lastName
	 * @param  DateTime|string|null  $dateOfBirth
	 * @param  string|null  $phoneNumber
	 * @param  int|null  $sexType
	 * @param  string|null  $nationality
	 *
	 * @noinspection PhpMissingParentConstructorInspection
	 */
	public function __construct( $email = null, string $password = null, string $firstName = null, string $lastName = null, $dateOfBirth = null, string $phoneNumber = null, int $sexType = null, string $nationality = null ) {
		$this->set_email( $email );
		$this->set_password( $password );
		$this->set_first_name( $firstName );
		$this->set_last_name( $lastName );
		$this->set_date_of_birth( $dateOfBirth );
		$this->set_phone_number( $phoneNumber );
		$this->set_sex_type( $sexType );
		$this->set_nationality( $nationality );
	}

	/**
	 * @param  string|null  $password
	 */
	public function set_password( ?string $password ): void {
		$this->password = $password ?? $this->password;
	}

	/**
	 * @return string
	 */
	public function get_password(): string {
		return $this->password ?? '';
	}

	public function jsonSerialize(): array {
		$arr = array_merge(
			get_object_vars( $this ),
			parent::jsonSerialize()
		);

		unset( $arr['legalName'] );
		unset( $arr['country'] );
		unset( $arr['blobPictureName'] );
		unset( $arr['contractbookDocumentId'] );
		unset( $arr['verified'] );

		return $arr;
	}
}
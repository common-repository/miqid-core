<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class Login extends Base {

	/** @var string|null */
	private $emailOrPhone;
	/** @var string|null */
	private $password;

	public function __construct(
		$emailOrPhone = null,
		$password = null
	) {
		if ( is_array( $emailOrPhone ) ) {
			$this->parse_array( $emailOrPhone );
		}

		if ( isset( $emailOrPhone ) ) {
			$this->set_email_or_phone( $emailOrPhone );
		}
		if ( isset( $password ) ) {
			$this->set_password( $password );
		}
	}

	/**
	 * @param string|null $emailOrPhone
	 */
	public function set_email_or_phone( ?string $emailOrPhone ): void {
		$this->emailOrPhone = $emailOrPhone;
	}

	/**
	 * @param string|null $password
	 */
	public function set_password( ?string $password ): void {
		$this->password = $password;
	}

	/**
	 * @return string|null
	 */
	public function get_email_or_phone(): ?string {
		return $this->emailOrPhone;
	}

	public function get_email(): ?string {
		return filter_var( $this->emailOrPhone, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE );
	}

	public function get_phone(): ?string {
		return ! $this->get_email() ? $this->emailOrPhone : null;
	}

	/**
	 * @return string|null
	 */
	public function get_password(): ?string {
		return $this->password;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
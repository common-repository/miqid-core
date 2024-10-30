<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Base;

class TwoFactorAuthentication extends Base {
	/** @var string|null */
	private $EmailOrPhone;
	/** @var string|null */
	private $Password;
	/** @var string|null */
	private $CallbackUrl;

	public function __construct( ?array $TwoFactorAuthentication = null ) {
		if ( is_array( $TwoFactorAuthentication ) ) {
			$this->parse_array( $TwoFactorAuthentication );
		}
	}

	/**
	 * @param  string  $EmailOrPhone
	 *
	 * @return self
	 */
	public function set_email_or_phone( string $EmailOrPhone ): self {
		$this->EmailOrPhone = $EmailOrPhone;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_email_or_phone(): ?string {
		return $this->EmailOrPhone;
	}

	/**
	 * @param  string  $Password
	 *
	 * @return self
	 */
	public function set_password( string $Password ): self {
		$this->Password = $Password;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_password(): ?string {
		return $this->Password;
	}

	/**
	 * @param  string  $CallbackUrl
	 *
	 * @return self
	 */
	public function set_callback_url( string $CallbackUrl ): self {
		$this->CallbackUrl = $CallbackUrl;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_callback_url(): ?string {
		return $this->CallbackUrl;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
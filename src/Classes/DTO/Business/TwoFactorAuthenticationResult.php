<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Base, Enum\TwoFactorAuthenticationStatus};

class TwoFactorAuthenticationResult extends Base {
	/** @var TwoFactorAuthenticationStatus|null */
	private $Status;
	/** @var string|null */
	private $TwoFactorAuthenticationId;

	public function __construct( ?array $TwoFactorAuthenticationResult = null ) {
		if ( is_array( $TwoFactorAuthenticationResult ) ) {
			$this->parse_array( $TwoFactorAuthenticationResult );
		}
	}

	/**
	 * @param  TwoFactorAuthenticationStatus|string|null  $Status
	 */
	public function set_status( $Status ): void {
		if ( is_string( $Status ) ) {
			$Status = TwoFactorAuthenticationStatus::$Status();
		}
		$this->Status = new TwoFactorAuthenticationStatus( $Status );
	}

	/**
	 * @return TwoFactorAuthenticationStatus|null
	 */
	public function get_status(): ?TwoFactorAuthenticationStatus {
		return $this->Status;
	}

	/**
	 * @param  string|null  $TwoFactorAuthenticationId
	 */
	public function set_two_factor_authentication_id( ?string $TwoFactorAuthenticationId ): void {
		$this->TwoFactorAuthenticationId = $TwoFactorAuthenticationId;
	}

	/**
	 * @return string|null
	 */
	public function get_two_factor_authentication_id(): ?string {
		return $this->TwoFactorAuthenticationId;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
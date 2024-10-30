<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class TwoFactorAuthenticationStatus extends Enum {
	const Declined  = 0;
	const Accepted  = 1;
	const Postponed = 2;

	protected $value;

	public function __construct( $value ) {
		if ( is_string( $value ) ) {
			$value = self::$value();
		}
		parent::__construct( $value );
	}
}
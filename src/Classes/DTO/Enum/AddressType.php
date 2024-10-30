<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class AddressType extends Enum {
	const PrimaryAddress = 1;
	const WorkAddress    = 2;
	const POBox          = 3;
	const Other          = 9;

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );
	}
}
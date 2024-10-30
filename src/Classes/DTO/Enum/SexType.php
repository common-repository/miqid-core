<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class SexType extends Enum {
	const NotSet = 0;
	const Male   = 1;
	const Female = 2;
	const Other  = 3;

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );
		_x( 'Male', 'SexType', 'miqid-core' );
		_x( 'Female', 'SexType', 'miqid-core' );
		_x( 'Other', 'SexType', 'miqid-core' );
	}
}
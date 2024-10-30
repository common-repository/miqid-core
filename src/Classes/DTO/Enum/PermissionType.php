<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class PermissionType extends Enum {
	const Authority = 3;
	const Shopping  = 2;
	const Private   = 1;
	const NotSet    = 0;

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );

		_x( 'Authority', 'PermissionType', 'miqid-core' );
		_x( 'Shopping', 'PermissionType', 'miqid-core' );
		_x( 'Private', 'PermissionType', 'miqid-core' );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class Theme extends Enum {
	const Dark  = "Dark";
	const Light = "Light";

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );
		_x( 'Dark', 'Theme', 'miqid-core' );
		_x( 'Light', 'Theme', 'miqid-core' );
	}
}
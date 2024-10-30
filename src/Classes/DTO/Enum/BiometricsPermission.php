<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

final class BiometricsPermission extends Enum {
	const UseBiometricsToApprove = 'UseBiometricsToApprove';
	const UseBiometricsToRecover = 'UseBiometricsToRecover';

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );

		_x( 'UseBiometricsToApprove', 'BiometricsPermission', 'miqid-core' );
		_x( 'UseBiometricsToRecover', 'BiometricsPermission', 'miqid-core' );
	}
}
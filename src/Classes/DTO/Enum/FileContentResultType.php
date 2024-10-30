<?php

namespace MIQID\Plugin\Core\Classes\DTO\Enum;

use MyCLabs\Enum\Enum;

class FileContentResultType extends Enum {
	const PassportImage            = 'PassportImage';
	const PassportFaceImage        = 'PassportFaceImage';
	const DriversLicenseImage      = 'DriversLicenseImage';
	const DriversLicenseFaceImage  = 'DriversLicenseFaceImage';
	const HealthInsuranceCardImage = 'HealthInsuranceCardImage';
	const NotSet                   = 'NotSet';

	protected $value;

	public function __construct( $value ) {
		parent::__construct( $value );
	}
}
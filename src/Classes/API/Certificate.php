<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\Classes\DTO\DriversLicense;
use MIQID\Plugin\Core\Classes\DTO\FileContentResult;
use MIQID\Plugin\Core\Classes\DTO\HealthInsuranceCard;
use MIQID\Plugin\Core\Classes\DTO\Passport;
use MIQID\Plugin\Core\Util;

class Certificate extends Base {
	private static $instance;

	const    Passport        = 1;
	const    DriversLicense  = 2;
	const    HealthInsurance = 3;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
	}

	function GetCertificateImage( $type = 0 ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'type' => $type ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new FileContentResult( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function GetPassportCertificateInformation() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new Passport( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function GetDriversLicenseCertificateInformation() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new DriversLicense( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function GetHealthInsuranceCardCertificateInformation() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new HealthInsuranceCard( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function DoesProfileHaveACertificate() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return $HttpResponse->get_body();
		}

		return $HttpResponse;
	}
}




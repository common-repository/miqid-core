<?php

namespace MIQID\Plugin\Core\Classes\API\Business;

use MIQID\Plugin\Core\Classes\DTO\{Business\DriversLicense, Business\HealthInsuranceCard, Business\Passport, Enum\FileContentResultType, FileContentResult};
use MIQID\Plugin\Core\Util;

class Certificate extends Base {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	// <editor-fold desc="Passport">

	function GetProfilePassportImage( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [
				'profileId' => $profileId,
			], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return ( new FileContentResult( $HttpResponse->get_body() ) )
				->set_file_content_result_type( FileContentResultType::PassportImage );
		}

		return $HttpResponse;
	}

	function GetProfilePassportFaceImage( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [
				'profileId' => $profileId,
			], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return ( new FileContentResult( $HttpResponse->get_body() ) )
				->set_file_content_result_type( FileContentResultType::PassportFaceImage );
		}

		return $HttpResponse;
	}

	function GetPassportCertificateInformation( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [
				'profileId' => $profileId,
			], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new Passport( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	// </editor-fold>

	// <editor-fold desc="Drivers License">

	function GetProfileDriversLicenseImage( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [
				'profileId' => $profileId,
			], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return ( new FileContentResult( $HttpResponse->get_body() ) )
				->set_file_content_result_type( FileContentResultType::DriversLicenseImage );
		}

		return $HttpResponse;
	}

	function GetDriversLicenseFaceImage( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return ( new FileContentResult( $HttpResponse->get_body() ) )
				->set_file_content_result_type( FileContentResultType::DriversLicenseFaceImage );
		}

		return $HttpResponse;
	}

	function GetDriversLicenseCertificateInformation( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new DriversLicense( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	// </editor-fold>

	// <editor-fold desc="HealthInsuranceCard">

	function GetHealthInsuranceCardImage( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return ( new FileContentResult( $HttpResponse->get_body() ) )
				->set_file_content_result_type( FileContentResultType::HealthInsuranceCardImage );
		}

		return $HttpResponse;
	}

	function GetHealthInsuranceCardCertificateInformation( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new HealthInsuranceCard( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	// </editor-fold>
}
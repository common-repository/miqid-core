<?php

namespace MIQID\Plugin\Core\Classes\API\Business;

use MIQID\Plugin\Core\Util;

class Profile extends Base {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	function GetProfile( $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);
		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new \MIQID\Plugin\Core\Classes\DTO\Business\Profile( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function GetProfileImage( string $profileId ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return $HttpResponse->get_body();
		}

		return $HttpResponse;
	}

}
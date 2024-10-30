<?php

namespace MIQID\Plugin\Core\Classes\API\Business;

use MIQID\Plugin\Core\Classes\DTO\Business\TwoFactorAuthentication;
use MIQID\Plugin\Core\Classes\DTO\Business\TwoFactorAuthenticationResult;
use MIQID\Plugin\Core\Util;

class UserAuthentication extends Base {
	private static $instance;

	public static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	function TwoFactorAuthentication( TwoFactorAuthentication $TwoFactorAuthentication ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			$TwoFactorAuthentication,
			Util::get_miqid_core_settings()->get_JWT() );

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return $HttpResponse->get_body();
		}

		return $HttpResponse;
	}

	function TwoFactorAuthenticationResult( ?array $data = null ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			$data,
			Util::get_miqid_core_settings()->get_JWT() );
		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new TwoFactorAuthenticationResult( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}
}
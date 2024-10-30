<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\{Classes\DTO\HttpResponse, Classes\DTO\Profile as dtoProfile, Classes\DTO\ProfilePicture, Util};

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

	/**
	 * @return dtoProfile|HttpResponse
	 */
	function GetProfile() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoProfile( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	/**
	 * @param dtoProfile $profile
	 *
	 * @return dtoProfile|HttpResponse
	 */
	function UpdateProfile( dtoProfile $profile ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			json_encode( $profile ),
			Util::get_user_jwt(),
			[ 'method' => 'PUT' ]
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoProfile( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	/**
	 * @return ProfilePicture|HttpResponse
	 */
	function GetProfilePicture() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new ProfilePicture( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}


}
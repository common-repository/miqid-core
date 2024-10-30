<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\{Classes\DTO\Address as dtoAddress, Classes\DTO\HttpResponse, Util};

class Address extends Base {
	private static $instance;

	public static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
	}

	/**
	 * @return dtoAddress|HttpResponse
	 */
	function GetPrimaryAddress() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);
		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoAddress( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	/**
	 * @param dtoAddress $address
	 *
	 * @return dtoAddress|HttpResponse
	 */
	function UpdateAddress( dtoAddress $address ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			json_encode( $address ),
			Util::get_user_jwt(),
			[ 'method' => 'PUT' ]
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoAddress( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}
}
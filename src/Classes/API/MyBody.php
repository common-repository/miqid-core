<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, MyBody as dtoMyBody};
use MIQID\Plugin\Core\Util;

class MyBody extends Base {
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
	 * @return dtoMyBody|HttpResponse
	 */
	function GetMyBody() {
		$HttpResponse = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ ),
			Util::get_user_jwt()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoMyBody( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	function UpdateMyBody( dtoMyBody $MyBody ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			$MyBody,
			Util::get_user_jwt(),
			[ 'method' => 'PUT' ]
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new dtoMyBody( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

}
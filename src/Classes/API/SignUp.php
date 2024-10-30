<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\Classes\DTO\{JWT as dtoJWT, SignUp as dtoSignUp};

class SignUp extends Base {
	private static $_instance;

	public static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
	}

	function SignUp( dtoSignUp $signUp ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			json_encode( $signUp )
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 201 ] ) ) {
			return new dtoJWT( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}
}
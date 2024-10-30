<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, JWT, Login};

class Authentication extends Base {
	private static $_instance;

	static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
	}

	/**
	 * @param Login $login
	 *
	 * @return JWT|HttpResponse
	 */
	function AuthenticateLogin( Login $login ) {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ ),
			$login
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new JWT( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	/**
	 * @param JWT $JWT
	 *
	 * @return JWT|HttpResponse
	 */
	function ValidateRefreshToken( JWT $JWT ) {
		$HttpResponse = $this->RemotePost(
			add_query_arg( [
				'jwt' => $JWT->get_jwt(),
			], $this->GetEndpoint( __FUNCTION__ ) )
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return new JWT( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}

	/**
	 * @return JWT|HttpResponse
	 */
	function RevokeRefreshToken() {
		$HttpResponse = $this->RemotePost(
			$this->GetEndpoint( __FUNCTION__ )
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200, 204 ] ) ) {
			return new JWT( '' );
		}

		return $HttpResponse;
	}
}
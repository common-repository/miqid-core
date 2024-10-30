<?php

namespace MIQID\Plugin\Core\Classes\API;

class MiscCompany extends Base {
	private static $_instance;

	public static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
	}

	function GetProfileIdFromAccountId( $accountId, $JWT ) {
		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'accountId' => $accountId ], $this->GetEndpoint( __FUNCTION__ ) ),
			$JWT
		);
		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			return $HttpResponse->get_body();
		}

		return $HttpResponse;
	}
}
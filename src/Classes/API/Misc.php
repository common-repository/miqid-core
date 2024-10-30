<?php

namespace MIQID\Plugin\Core\Classes\API;

class Misc extends Base {
	private static $_instance;

	public static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
	}

	/**
	 * @return $this|false
	 */
	function Ping() {
		$Response = $this->RemoteGet(
			$this->GetEndpoint( __FUNCTION__ )
		);

		if ( in_array( $Response->get_response_code(), [ 200 ] ) ) {
			return $this;
		}

		return false;
	}
}
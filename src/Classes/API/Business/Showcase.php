<?php

namespace MIQID\Plugin\Core\Classes\API\Business;

use MIQID\Plugin\Core\Util;

class Showcase extends Base {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
	}

	function GetShowcaseInformation( $profileId ) {
		if ( $cache = Util::cache_handler( get_class( $this ), __FUNCTION__, $profileId ) ) {
			return new \MIQID\Plugin\Core\Classes\DTO\Business\Showcase( $cache );
		}

		$HttpResponse = $this->RemoteGet(
			add_query_arg( [ 'profileId' => $profileId, ], $this->GetEndpoint( __FUNCTION__ ) ),
			Util::get_miqid_core_settings()->get_JWT()
		);

		if ( in_array( $HttpResponse->get_response_code(), [ 200 ] ) ) {
			Util::cache_handler( get_class( $this ), __FUNCTION__, $profileId, $HttpResponse->get_body( false ) );

			return new \MIQID\Plugin\Core\Classes\DTO\Business\Showcase( $HttpResponse->get_body() );
		}

		return $HttpResponse;
	}
}
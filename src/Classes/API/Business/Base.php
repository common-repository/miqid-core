<?php

namespace MIQID\Plugin\Core\Classes\API\Business;

use MIQID\Plugin\Core\Classes\API\Base as APIBase;
use MIQID\Plugin\Core\Util;

abstract class Base extends APIBase {
	/**
	 * @param string|array $Function = [
	 *    'host' => '',
	 *    'version' => '',
	 *    'class' => '',
	 *    'function' => '$Function',
	 * ]
	 *
	 * @return string
	 */
	public function GetEndpoint( $Function ): string {
		$settings = Util::get_miqid_core_settings();
		$uri      = [
			'host'    => $settings->get_endpoint_business()->get_host(),
			'version' => $settings->get_endpoint_business()->get_version(),
		];
		if ( is_string( $Function ) ) {
			$uri['function'] = $Function;
		} else if ( is_array( $Function ) ) {
			$uri = array_replace( $uri, $Function );
		}

		$uri = array_filter( $uri );

		return parent::GetEndpoint( $uri );
	}
}
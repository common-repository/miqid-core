<?php

namespace MIQID\Plugin\Core\Classes\API;

use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, JWT};
use MIQID\Plugin\Core\Util;
use ReflectionClass;

abstract class Base {
	/**
	 * @return self;
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	abstract static function Instance();

	/**
	 * @param $url
	 * @param  null  $JWT
	 * @param  array  $args
	 *
	 * @return HttpResponse
	 */
	protected function RemoteGet( $url, $JWT = null, array $args = [] ): HttpResponse {
		if ( is_string( $JWT ) ) {
			$JWT = ( new JWT() )->set_jwt( $JWT );
		}

		$default = [ 'headers' => [ 'Content-Type' => 'application/json; charset=utf-8' ] ];

		if ( $JWT instanceof JWT && ! empty( $JWT->get_jwt() ) ) {
			$default['headers']['Authorization'] = sprintf( 'bearer %s', $JWT->get_jwt() );
		}

		$args = wp_parse_args( $args, $default );

		return new HttpResponse( wp_remote_get( $url, $args ), $url, $args );
	}

	/**
	 * @param $url
	 * @param  string|array|object  $body
	 * @param  null  $JWT
	 * @param  array  $args
	 *
	 * @return HttpResponse
	 */
	protected function RemotePost( $url, $body = '', $JWT = null, array $args = [] ): HttpResponse {

		if ( is_object( $body ) || is_array( $body ) ) {
			$body = json_encode( $body );
		}
		if ( ! is_null( $JWT ) && is_string( $JWT ) ) {
			$JWT = new JWT( $JWT );
		}

		$default = [
			'headers' => [ 'Content-Type' => 'application/json; charset=utf-8' ],
			'method'  => 'POST',
			'body'    => $body,
		];

		$args = wp_parse_args( $args, $default );

		if ( $JWT instanceof JWT && ! empty( $JWT->get_jwt() ) ) {
			$args['headers']['Authorization'] = sprintf( 'bearer %s', $JWT->get_jwt() );
		}

		if ( $args['headers']['Content-Type'] === $default['headers']['Content-Type'] ) {
			$args['data_format'] = 'body';
		}

		return new HttpResponse( wp_remote_post( $url, $args ), $url, $args );
	}

	/**
	 * @param  string|array  $Function  = [
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
		$class    = new ReflectionClass( $this );

		$uri = [
			'host'    => $settings->get_endpoint_private()->get_host(),
			'version' => $settings->get_endpoint_private()->get_version(),
			'class'   => $class->getShortName(),
		];
		if ( is_string( $Function ) ) {
			$uri['function'] = $Function;
		} elseif ( is_array( $Function ) ) {
			$uri = array_replace( $uri, $Function );
		}

		$uri = array_filter( $uri );

		return implode( DIRECTORY_SEPARATOR, $uri );
	}
}
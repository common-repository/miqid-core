<?php

namespace MIQID\Plugin\Core;

use MIQID\Plugin\Core\{Frontend\Frontend};

class Init {

	public function __construct() {
		Frontend::Instance();

		if ( is_admin() ) {
			Admin\Init::Instance();
		}

		add_filter( 'pre_http_request', [ $this, 'pre_http_request' ], 10, 3 );
	}

	function pre_http_request( $preempt, $parsed_args, $url ) {

		if ( preg_match( '/api\.private\.(?:dev\.)?miqid\.com/i', $url ) ) {
			if ( preg_match( '/Authentication\/AuthenticateLogin/i', $url ) ) {
				return $preempt;
			}
			$page = sprintf( '%s/%s', site_url(), trim( $_SERVER['REQUEST_URI'], ' \t\n\r\0\x0B\/' ) );
			//if ( ! get_transient( md5( $page ) ) ) {
				$content = sprintf( 'There has been a request against the wrong endpoint.

It has been registered from the following page:
%s

Towards the following endpoint.
%s

Best Regards
%s',
					$page,
					$url,
					$_SERVER['HTTP_HOST']
				);

				wp_mail(
					'miqid-wrong-endpoint@karlog-it.dk,lea@karlog-it.dk',
					sprintf( 'Wrong endpoint in use - %s', get_bloginfo( 'name' ) ),
					$content );

				set_transient( md5( $page ), $content, HOUR_IN_SECONDS );
			//}
		}

		return $preempt;
	}

}
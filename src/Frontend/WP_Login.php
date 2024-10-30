<?php

namespace MIQID\Plugin\Core\Frontend;

use MIQID\Plugin\Core\Classes\API\{Authentication};
use MIQID\Plugin\Core\Classes\API\Business\{Profile};
use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, Login};
use MIQID\Plugin\Core\Util;

class WP_Login {
	private static $_instance;

	public static function Instance(): self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_action( 'login_init', [ $this, '_do_login' ] );
		add_action( 'login_footer', [ $this, '_miqid_footer' ], 50 );
	}


	function _do_login() {
		$errors = null;
		if ( ! filter_var( $_GET['miqid'] ?? false, FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			wp_clear_auth_cookie();

			if ( $login = new Login( sanitize_email( $_POST['log'] ), sanitize_text_field( $_POST['pwd'] ) ) ) {
				if ( ( $JWT = Authentication::Instance()->AuthenticateLogin( $login ) ) && ! $JWT instanceof HttpResponse ) {
					if ( ! $user = get_user_by( 'email', $login->get_email() ) ) {
						$user_id = wp_create_user( sanitize_user( $login->get_email() ), wp_generate_password(), $login->get_email() );
						if ( ! is_wp_error( $user_id ) ) {
							$user = get_user_by( 'id', $user_id );
						}
					}

					wp_set_current_user( $user->ID );
					wp_set_auth_cookie( $user->ID );

					Util::update_user_jwt( $JWT );

					if ( ( $profile = Profile::Instance()->GetProfile(Util::get_profileId()) ) && ! $profile instanceof HttpResponse ) {
						$user->first_name   = $profile->get_first_name();
						$user->last_name    = $profile->get_last_name();
						$user->display_name = $profile->get_full_name();

						$user_id = wp_update_user( $user );
						if ( is_wp_error( $user_id ) ) {
							wp_die( sprintf( '<pre>%s</pre>', print_r( $user_id->get_error_message(), true ) ) );
						}
					}

					$redirect_to = $_GET['redirect_to'] ?? null;
					if ( current_user_can( 'administrator' ) && is_null( $redirect_to ) ) {
						$redirect_to = admin_url();
					}

					wp_redirect( $redirect_to ?? home_url() );
				} else {
					$errors = $JWT->get_response_message();
				}
			}
		}
		?>
        <!DOCTYPE html>
        <html <?php language_attributes() ?>>
        <head>
            <title><?= __( 'MIQID Login' ) ?></title>
            <meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>">
            <meta name="viewport" content="width=device-width">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
			<?php
			wp_enqueue_style( 'login' );
			do_action( 'login_enqueue_scripts' );
			do_action( 'login_head' );
			$action  = 'show';
			$classes = array_filter( [
				'login',
				'no-js',
				'login-action-' . $action,
				'wp-core-ui',
				sprintf( 'locale-%s', sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) ) ),
				is_rtl() ? 'rtl' : null,
			] );

			$classes = apply_filters( 'login_body_class', $classes, $action );
			?>
        </head>
        <body class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
        <h1><a href="https://miqid.com/" target="_blank"></a></h1>
        <form name="loginform" id="loginform" action="<?= esc_attr( add_query_arg( [] ) ) ?>" method="post">
			<?php if ( ! empty( $errors ) ) {
				printf( '<div id="login_error">%s</div>', apply_filters( 'login_errors', $errors ) );
			} ?>
            <p>
                <label for="user_login"><?= __( 'Email', 'miqid-core' ) ?></label>
                <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off"/>
            </p>

            <div class="user-pass-wrap">
                <label for="user_pass"><?= __( 'Password', 'miqid-core' ) ?></label>
                <div class="wp-pwd">
                    <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="20"/>
                    <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Vis adgangskode">
                        <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <div>
                <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log ind"/>
                    <input type="hidden" name="redirect_to" value="http://localhost:8080/wp-admin/"/>
                    <input type="hidden" name="testcookie" value="1"/>
                </p>
                <br style="clear: both"/>
            </div>

        </form>
        <style>
            body {
                background: rgb(157, 208, 204);
                background: linear-gradient(-45deg, rgba(5, 88, 125, 1) 0%, rgba(157, 208, 204, 1) 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                flex-direction: column;
            }

            .login h1 {
                margin-top: -100px;
            }

            .login h1 a {
                background-image: url('<?=Util::get_assets_images_url()?>/MIQID-Logo.svg');
                width: 210px;
                height: 60px;
                background-size: contain;
            }
        </style>
        </body>
        </html>
		<?php
		exit();
	}

	function _miqid_footer() {
		?>
        <a id="miqid_button_login"
           class="button button-primary"
           style="width: 100%"
           href="<?= add_query_arg( [ 'miqid' => true, 'loggedout' => null ] ) ?>">
        </a>
        <script>
          (function($) {
            let _miqid_log = $('<div id=\"miqid_login\"></div>'),
                _loginform = $('#loginform');

            _miqid_log.append($('#miqid_button_login'));

            _loginform.append('<br class=\"clr\" />');
            _loginform.append(_miqid_log);
          })(jQuery);
        </script>
        <style>
            #loginform .clr {
                clear: right;
            }

            #loginform #miqid_login {
                box-sizing: border-box;
                margin: 10px 0;
                width: 100%;
                background: rgb(157, 208, 204);
                background: linear-gradient(-45deg, rgba(5, 88, 125, 1) 0%, rgba(157, 208, 204, 1) 100%);
            }

            #loginform #miqid_login * {
                box-sizing: border-box;
            }

            #loginform #miqid_login .button-primary {
                display: flex;
                float: none;
                justify-content: center;
                align-items: center;
            }

            #loginform #miqid_login #miqid_button_login {
                background-image: url('<?=Util::get_assets_images_url()?>/miqid-logo.svg');
                background-position: center;
                background-repeat: no-repeat;
                background-size: auto 24px;
                height: 32px;
            }
        </style>
		<?php
	}
}
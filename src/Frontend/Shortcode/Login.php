<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Classes\API\{Authentication};
use MIQID\Plugin\Core\Classes\API\Business\{Profile};
use Elementor\Plugin;
use MIQID\Plugin\Core\Classes\DTO\{JWT, Profile as dtoProfile};
use MIQID\Plugin\Core\Util;

class Login {
	private static $instance;

	static function Instance(): Login {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'miqid-login', [ $this, '_miqid_login' ] );

		add_action( 'admin_post_nopriv_miqid_login', [ $this, '_login_handler' ] );
		add_action( 'admin_post_miqid_login', [ $this, '_login_handler' ] );
	}

	function _miqid_login( $atts ): string {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts( [
			'label_login'       => __( 'Username' ),
			'placeholder_login' => '',
			'label_pass'        => __( 'Password' ),
			'placeholder_pass'  => '',
			'btn_login'         => __( 'Login' ),
			'redirect_to'       => get_permalink(),
			'logged_in'         => __( 'Du er allerede logget ind' ),
			'auto_redirect'     => false,
		], $atts );

		if ( ( $Profile = Profile::Instance()->GetProfile(Util::get_profileId()) ) && $Profile instanceof dtoProfile ) {
			if ( class_exists( '\Elementor\Plugin' ) &&
				 Plugin::$instance->editor->is_edit_mode() ) {
				return $atts['logged_in'];
			}

			if ( filter_var( $atts['auto_redirect'], FILTER_VALIDATE_BOOLEAN ) ) {
				return sprintf( '<script>location.href = "%s"</script>', esc_url( $atts['redirect_to'] ) );
			}

			return $atts['logged_in'];
		}

		$error = filter_input( INPUT_GET, 'error' );
		$error = isset( $error ) ? sprintf( '<p class="error error-message">%s</p>', __( $error, 'miqid-core' ) ) : null;

		return sprintf( '<form name="miqid-login" id="miqid-login" method="post" action="%7$s">
	%9$s
    <p class="login-username">
        <label for="user_login">%1$s</label>
        <input type="text"
               name="log"
               id="user_login"
               class="input"
               value=""
               placeholder="%2$s"
               size="20"/>
    </p>
    <p class="login-password">
        <label for="user_pass">%3$s</label>
        <input type="password"
               name="pwd"
               id="user_pass"
               class="input"
               value=""
               placeholder="%4$s"
               size="20"/>
    </p>
    <p class="login-submit">
        <button type="submit" class="button button-primary">%5$s</button>
        <input type="hidden" name="redirect_to" value="%6$s">
        <input type="hidden" name="action" value="miqid_login">
        %8$s
    </p>
</form>',
			$atts['label_login'],
			$atts['placeholder_login'],
			$atts['label_pass'],
			$atts['placeholder_pass'],
			$atts['btn_login'],
			$atts['redirect_to'],
			esc_attr( admin_url( 'admin-post.php' ) ),
			wp_nonce_field( - 1, '_wpnonce', true, false ),
			$error
		);
	}

	function _login_handler() {
		wp_clear_auth_cookie();
		$_wp_http_referer = sanitize_text_field( $_POST['_wp_http_referer'] ?? '' );
		$_redirect        = sanitize_text_field( $_POST['redirect_to'] ?? $_wp_http_referer );

		$Login = new \MIQID\Plugin\Core\Classes\DTO\Login(
			sanitize_text_field( $_POST['log'] ),
			sanitize_text_field( $_POST['pwd'] )
		);


		if ( $JWT = Authentication::Instance()->AuthenticateLogin( $Login ) ) {
			if ( $JWT instanceof JWT ) {
				$user = get_user_by( 'email', $Login->get_email() );
				if ( ! $user && ( $user_id = wp_create_user( $Login->get_email(), wp_generate_password(), $Login->get_email() ) ) && ! is_wp_error( $user_id ) ) {
					$user = get_user_by( 'id', $user_id );
				}

				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );

				Util::update_user_jwt( $JWT );

				if ( ( $profile = Profile::Instance()->GetProfile() ) && $profile instanceof dtoProfile ) {
					$user->first_name = $profile->get_first_name();
					$user->last_name  = $profile->get_last_name();
					wp_update_user( $user );

					//wp_die($_redirect);
					wp_redirect( $_redirect );

				} else {
					wp_redirect( add_query_arg( [
						'error' => $profile->get_response_message(),
					], $_wp_http_referer ) );
				}
			} else {
				wp_redirect( add_query_arg( [
					'error' => $JWT->get_response_message(),
				], $_wp_http_referer ) );
			}
		}
	}
}
<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\Business\TwoFactorAuthentication;
use MIQID\Plugin\Core\Classes\DTO\HttpResponse;

class UserAuthentication extends \MIQID\Plugin\Core\Classes\API\Business\UserAuthentication {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'TwoFactorAuthentication', [ $this, 'Shortcode_TwoFactorAuthentication' ] );
		add_action( 'admin_post_miqid_twofactorauthentication', [ $this, 'Log_TwoFactorAuthentication' ] );
		add_action( 'admin_post_nopriv_miqid_twofactorauthentication', [ $this, 'Log_TwoFactorAuthentication' ] );
	}

	function get_LogTwoFactorAuthenticationFolder(): string {
		$folder = implode( DIRECTORY_SEPARATOR, [ wp_upload_dir()['basedir'], 'miqid', 'callback' ] );
		if ( ! file_exists( $folder ) ) {
			wp_mkdir_p( $folder );
		}

		return $folder;
	}

	function get_LogTwoFactorAuthenticationFile(): string {
		return sprintf( '%s.json', implode( DIRECTORY_SEPARATOR, [
			$this->get_LogTwoFactorAuthenticationFolder(),
			$_REQUEST['cookie'] ?? ( $_COOKIE['miqid_callback'] ?? wp_generate_uuid4() ),
		] ) );
	}

	function Log_TwoFactorAuthentication() {
		$req = $_REQUEST;

		if ( $input = file_get_contents( 'php://input' ) ) {
			if ( is_array( $input ) ) {
				$req = array_merge( $req, $input );
			} else if ( is_string( $input ) ) {
				$input = json_decode( $input, true );
				$req   = array_merge( $req, $input );
			}
		}

		file_put_contents( $this->get_LogTwoFactorAuthenticationFile(),
			json_encode( $req, JSON_PRETTY_PRINT ) );
	}

	function Shortcode_TwoFactorAuthentication( $atts ) {
		$TwoFactorAuthentication = new TwoFactorAuthentication( $_REQUEST );

		$cookie_name = basename( $this->get_LogTwoFactorAuthenticationFile(), '.json' );

		printf( '<script>document.cookie = "miqid_callback=%1$s";</script>', $cookie_name );
		if ( file_exists( $this->get_LogTwoFactorAuthenticationFile() ) ) {
			printf( '<pre>%s</pre>
<p>
    <button onclick="clearCookie()">Clear</button>
</p>
<script>
  const clearCookie = () => {
    document.cookie = \'miqid_callback= ; expires = Thu, 01 Jan 1970 00:00:00 GMT\';
    let _urlSearch = new URLSearchParams(location.search);
    _urlSearch.delete(`cookie`);
    location.href = `?${_urlSearch.toString()}`;
  };
  setTimeout(() => location.href = location.href, 10000);
</script>', file_get_contents( $this->get_LogTwoFactorAuthenticationFile() ) );
		} else if ( ! empty( $TwoFactorAuthentication->get_email_or_phone() ) &&
		            ! empty( $TwoFactorAuthentication->get_password() ) ) {
			$TwoFactorAuthentication->set_callback_url( add_query_arg( [
				'action' => 'miqid_twofactorauthentication',
				'cookie' => $cookie_name,
			], admin_url( 'admin-post.php' ) ) );

			file_put_contents( $this->get_LogTwoFactorAuthenticationFile(), json_encode( [] ) );

			if ( ( $APITwoFactorAuthentication = \MIQID\Plugin\Core\Classes\API\Business\UserAuthentication::Instance()->TwoFactorAuthentication( $TwoFactorAuthentication ) ) &&
			     $APITwoFactorAuthentication instanceof HttpResponse ) {
				print_r( $APITwoFactorAuthentication );
			} else {
				print_r( $APITwoFactorAuthentication );
				printf( '<script>
  let urlParams = new URLSearchParams(location.search);
  urlParams.set(\'cookie\', \'%1$s\');
  location.href = `?${urlParams.toString()}`;
</script>', $cookie_name );
			}
		} else {
			printf( '<form method="post" action="?" class="">
    %1$s
    <p>
        <label for="EmailOrPhone" style="display: block">Email / Phone</label>
        <input type="text" name="EmailOrPhone" id="EmailOrPhone">
    </p>
    <p>
        <label for="Password" style="display: block">Adgangskode</label>
        <input type="password" name="Password" id="Password">        
    </p>
    <p>
		<button type="submit" class="default default-primary">Login</button>    
	</p>
</form>',
				wp_nonce_field( - 1, '_wpnonce', true, false ) );
		}
	}
}
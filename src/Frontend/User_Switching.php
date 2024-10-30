<?php


namespace MIQID\Plugin\Core\Frontend;

use WP_Session_Tokens;
use WP_User;
use WP_Admin_Bar;

class User_Switching {

	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * The name used to identify the application during a WordPress redirect.
	 *
	 * @var string
	 */
	public $application = 'WordPress/User Switching';

	/**
	 * Sets up all the filters and actions.
	 */
	public function init_hooks() {
		// Required functionality:
		add_filter( 'user_has_cap', [ $this, 'filter_user_has_cap' ], 10, 4 );
		add_filter( 'map_meta_cap', [ $this, 'filter_map_meta_cap' ], 10, 4 );
		add_action( 'plugins_loaded', [ $this, 'action_plugins_loaded' ], 1 );
		add_action( 'init', [ $this, 'action_init' ] );
		add_action( 'wp_logout', [ $this, 'user_switching_clear_olduser_cookie' ] );
		add_action( 'wp_login', [ $this, 'user_switching_clear_olduser_cookie' ] );

		// Nice-to-haves:
		add_filter( 'login_message', [ $this, 'filter_login_message' ], 1 );
		add_filter( 'removable_query_args', [ $this, 'filter_removable_query_args' ] );
		add_action( 'wp_meta', [ $this, 'action_wp_meta' ] );
		add_action( 'wp_footer', [ $this, 'action_wp_footer' ] );
		add_action( 'personal_options', [ $this, 'action_personal_options' ] );
		add_action( 'admin_bar_menu', [ $this, 'action_admin_bar_menu' ], 11 );
		add_action( 'bp_member_header_actions', [ $this, 'action_bp_button' ], 11 );
		add_action( 'bp_directory_members_actions', [ $this, 'action_bp_button' ], 11 );
		add_action( 'bbp_template_after_user_details', [ $this, 'action_bbpress_button' ] );
		add_action( 'switch_to_user', [ $this, 'forget_woocommerce_session' ] );
		add_action( 'switch_back_user', [ $this, 'forget_woocommerce_session' ] );
	}

	/**
	 * Defines the names of the cookies used by User Switching.
	 */
	public function action_plugins_loaded() {
		// User Switching's auth_cookie
		if ( ! defined( 'USER_SWITCHING_COOKIE' ) ) {
			define( 'USER_SWITCHING_COOKIE', 'wordpress_user_sw_' . COOKIEHASH );
		}

		// User Switching's secure_auth_cookie
		if ( ! defined( 'USER_SWITCHING_SECURE_COOKIE' ) ) {
			define( 'USER_SWITCHING_SECURE_COOKIE', 'wordpress_user_sw_secure_' . COOKIEHASH );
		}

		// User Switching's logged_in_cookie
		if ( ! defined( 'USER_SWITCHING_OLDUSER_COOKIE' ) ) {
			define( 'USER_SWITCHING_OLDUSER_COOKIE', 'wordpress_user_sw_olduser_' . COOKIEHASH );
		}
	}

	/**
	 * Outputs the 'Switch To' link on the user editing screen if the current user has permission to switch to them.
	 *
	 * @param  WP_User  $user  User object for this screen.
	 */
	public function action_personal_options( WP_User $user ) {
		$link = self::maybe_switch_url( $user );

		if ( ! $link ) {
			return;
		}

		?>
        <tr class="user-switching-wrap">
            <th scope="row"><?php echo esc_html_x( 'User Switching', 'User Switching title on user profile screen', 'miqid-core' ); ?></th>
            <td><a id="user_switching_switcher" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Switch&nbsp;To', 'miqid-core' ); ?></a></td>
        </tr>
		<?php
	}

	/**
	 * Returns whether the current logged in user is being remembered in the form of a persistent browser cookie
	 * (ie. they checked the 'Remember Me' check box when they logged in). This is used to persist the 'remember me'
	 * value when the user switches to another user.
	 *
	 * @return bool Whether the current user is being 'remembered'.
	 */
	public function remember(): bool {
		/** This filter is documented in wp-includes/pluggable.php */
		$cookie_life = apply_filters( 'auth_cookie_expiration', 172800, get_current_user_id(), false );
		$current     = wp_parse_auth_cookie( '', 'logged_in' );

		// Here we calculate the expiration length of the current auth cookie and compare it to the default expiration.
		// If it's greater than this, then we know the user checked 'Remember Me' when they logged in.
		return ( ( $current['expiration'] - time() ) > $cookie_life );
	}

	/**
	 * Loads localisation files and routes actions depending on the 'action' query var.
	 */
	public function action_init() {
		if ( ! isset( $_REQUEST['action'] ) ) {
			return;
		}

		$current_user = ( is_user_logged_in() ) ? wp_get_current_user() : null;

		switch ( $_REQUEST['action'] ) {

			// We're attempting to switch to another user:
			case 'switch_to_user':
				if ( isset( $_REQUEST['user_id'] ) ) {
					$user_id = absint( $_REQUEST['user_id'] );
				} else {
					$user_id = 0;
				}

				// Check authentication:
				if ( ! current_user_can( 'switch_to_user', $user_id ) ) {
					wp_die( esc_html__( 'Could not switch users.', 'miqid-core' ), 403 );
				}

				// Check intent:
				check_admin_referer( "switch_to_user_{$user_id}" );

				// Switch user:
				$user = self::switch_to_user( $user_id, self::remember() );
				if ( $user ) {
					$redirect_to = self::get_redirect( $user, $current_user );

					// Redirect to the dashboard or the home URL depending on capabilities:
					$args = [
						'user_switched' => 'true',
					];

					if ( $redirect_to ) {
						wp_safe_redirect( add_query_arg( $args, $redirect_to ) );
					} elseif ( ! current_user_can( 'read' ) ) {
						wp_safe_redirect( add_query_arg( $args, home_url() ) );
					} else {
						wp_safe_redirect( add_query_arg( $args, admin_url() ) );
					}
					exit;
				} else {
					wp_die( esc_html__( 'Could not switch users.', 'miqid-core' ), 404 );
				}

			// We're attempting to switch back to the originating user:
			case 'switch_to_olduser':
				// Fetch the originating user data:
				$old_user = self::get_old_user();
				if ( ! $old_user ) {
					wp_die( esc_html__( 'Could not switch users.', 'miqid-core' ), 400 );
				}

				// Check authentication:
				if ( ! self::authenticate_old_user( $old_user ) ) {
					wp_die( esc_html__( 'Could not switch users.', 'miqid-core' ), 403 );
				}

				// Check intent:
				check_admin_referer( "switch_to_olduser_{$old_user->ID}" );

				// Switch user:
				if ( self::switch_to_user( $old_user->ID, self::remember(), false ) ) {

					if ( ! empty( $_REQUEST['interim-login'] ) ) {
						$GLOBALS['interim_login'] = 'success'; // @codingStandardsIgnoreLine
						login_header( '' );
						exit;
					}

					$redirect_to = self::get_redirect( $old_user, $current_user );
					$args        = [
						'user_switched' => 'true',
						'switched_back' => 'true',
					];

					if ( $redirect_to ) {
						wp_safe_redirect( add_query_arg( $args, $redirect_to ) );
					} else {
						wp_safe_redirect( add_query_arg( $args, admin_url( 'users.php' ) ) );
					}
					exit;
				} else {
					wp_die( esc_html__( 'Could not switch users.', 'miqid-core' ), 404 );
				}

			// We're attempting to switch off the current user:
			case 'switch_off':
				// Check authentication:
				if ( ! current_user_can( 'switch_off' ) ) {
					/* Translators: "switch off" means to temporarily log out */
					wp_die( esc_html__( 'Could not switch off.', 'miqid-core' ) );
				}

				// Check intent:
				check_admin_referer( "switch_off_{$current_user->ID}" );

				// Switch off:
				if ( self::switch_off_user() ) {
					$redirect_to = self::get_redirect( null, $current_user );
					$args        = [
						'switched_off' => 'true',
					];

					if ( $redirect_to ) {
						wp_safe_redirect( add_query_arg( $args, $redirect_to ) );
					} else {
						wp_safe_redirect( add_query_arg( $args, home_url() ) );
					}
					exit;
				} else {
					/* Translators: "switch off" means to temporarily log out */
					wp_die( esc_html__( 'Could not switch off.', 'miqid-core' ) );
				}

		}
	}

	/**
	 * Fetches the URL to redirect to for a given user (used after switching).
	 *
	 * @param  WP_User|null  $new_user  Optional. The new user's WP_User object.
	 * @param  WP_User|null  $old_user  Optional. The old user's WP_User object.
	 *
	 * @return string The URL to redirect to.
	 */
	protected function get_redirect( WP_User $new_user = null, WP_User $old_user = null ): string {
		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to           = self::remove_query_args( wp_unslash( $_REQUEST['redirect_to'] ) );
			$requested_redirect_to = wp_unslash( $_REQUEST['redirect_to'] );
		} else {
			$redirect_to           = '';
			$requested_redirect_to = '';
		}

		if ( ! $new_user ) {
			/** This filter is documented in wp-login.php */
			$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $old_user );
		} else {
			/** This filter is documented in wp-login.php */
			$redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $new_user );
		}

		return $redirect_to;
	}


	/**
	 * Validates the old user cookie and returns its user data.
	 *
	 * @return false|WP_User False if there's no old user cookie or it's invalid, WP_User object if it's present and valid.
	 */
	public function get_old_user() {
		$cookie = self::user_switching_get_olduser_cookie();
		if ( ! empty( $cookie ) ) {
			$old_user_id = wp_validate_auth_cookie( $cookie, 'logged_in' );

			if ( $old_user_id ) {
				return get_userdata( $old_user_id );
			}
		}

		return false;
	}

	/**
	 * Authenticates an old user by verifying the latest entry in the auth cookie.
	 *
	 * @param  WP_User  $user  A WP_User object (usually from the logged_in cookie).
	 *
	 * @return bool Whether verification with the auth cookie passed.
	 */
	public function authenticate_old_user( WP_User $user ): bool {
		$cookie = self::user_switching_get_auth_cookie();
		if ( ! empty( $cookie ) ) {
			if ( self::secure_auth_cookie() ) {
				$scheme = 'secure_auth';
			} else {
				$scheme = 'auth';
			}

			$old_user_id = wp_validate_auth_cookie( end( $cookie ), $scheme );

			if ( $old_user_id ) {
				return ( $user->ID === $old_user_id );
			}
		}

		return false;
	}

	/**
	 * Adds a 'Switch back to {user}' link to the account menu, and a `Switch To` link to the user edit menu.
	 *
	 * @param  WP_Admin_Bar  $wp_admin_bar  The admin bar object.
	 */
	public function action_admin_bar_menu( WP_Admin_Bar $wp_admin_bar ) {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		if ( method_exists( $wp_admin_bar, 'get_node' ) ) {
			if ( $wp_admin_bar->get_node( 'user-actions' ) ) {
				$parent = 'user-actions';
			} else {
				return;
			}
		} elseif ( get_option( 'show_avatars' ) ) {
			$parent = 'my-account-with-avatar';
		} else {
			$parent = 'my-account';
		}

		$old_user = self::get_old_user();

		if ( $old_user ) {
			$wp_admin_bar->add_node( [
				'parent' => $parent,
				'id'     => 'switch-back',
				'title'  => esc_html( sprintf(
				/* Translators: 1: user display name; 2: username; */
					__( 'Switch back to %1$s (%2$s)', 'miqid-core' ),
					$old_user->display_name,
					$old_user->user_login
				) ),
				'href'   => add_query_arg( [
					'redirect_to' => urlencode( self::current_url() ),
				], self::switch_back_url( $old_user ) ),
			] );
		}

		if ( current_user_can( 'switch_off' ) ) {
			$url = self::switch_off_url( wp_get_current_user() );
			if ( ! is_admin() ) {
				$url = add_query_arg( [
					'redirect_to' => urlencode( self::current_url() ),
				], $url );
			}

			$wp_admin_bar->add_node( [
				'parent' => $parent,
				'id'     => 'switch-off',
				/* Translators: "switch off" means to temporarily log out */
				'title'  => esc_html__( 'Switch Off', 'miqid-core' ),
				'href'   => $url,
			] );
		}

		if ( ! is_admin() && is_author() && ( get_queried_object() instanceof WP_User ) ) {
			if ( $old_user ) {
				$wp_admin_bar->add_node( [
					'parent' => 'edit',
					'id'     => 'author-switch-back',
					'title'  => esc_html( sprintf(
					/* Translators: 1: user display name; 2: username; */
						__( 'Switch back to %1$s (%2$s)', 'miqid-core' ),
						$old_user->display_name,
						$old_user->user_login
					) ),
					'href'   => add_query_arg( [
						'redirect_to' => urlencode( self::current_url() ),
					], self::switch_back_url( $old_user ) ),
				] );
			} elseif ( current_user_can( 'switch_to_user', get_queried_object_id() ) ) {
				$wp_admin_bar->add_node( [
					'parent' => 'edit',
					'id'     => 'author-switch-to',
					'title'  => esc_html__( 'Switch&nbsp;To', 'miqid-core' ),
					'href'   => add_query_arg( [
						'redirect_to' => urlencode( self::current_url() ),
					], self::switch_to_url( get_queried_object() ) ),
				] );
			}
		}
	}

	/**
	 * Adds a 'Switch back to {user}' link to the Meta sidebar widget.
	 */
	public function action_wp_meta() {
		$old_user = self::get_old_user();

		if ( $old_user instanceof WP_User ) {
			$link = sprintf(
			/* Translators: 1: user display name; 2: username; */
				__( 'Switch back to %1$s (%2$s)', 'miqid-core' ),
				$old_user->display_name,
				$old_user->user_login
			);
			$url  = add_query_arg( [
				'redirect_to' => urlencode( self::current_url() ),
			], self::switch_back_url( $old_user ) );
			printf(
				'<li id="user_switching_switch_on"><a href="%s">%s</a></li>',
				esc_url( $url ),
				esc_html( $link )
			);
		}
	}

	/**
	 * Adds a 'Switch back to {user}' link to the WordPress footer if the admin toolbar isn't showing.
	 */
	public function action_wp_footer() {
		if ( is_admin_bar_showing() || did_action( 'wp_meta' ) ) {
			return;
		}

		/**
		 * Allows the 'Switch back to {user}' link in the WordPress footer to be disabled.
		 *
		 * @param  bool  $show_in_footer  Whether to show the 'Switch back to {user}' link in footer.
		 *
		 * @since 1.5.5
		 *
		 */
		if ( ! apply_filters( 'user_switching_in_footer', true ) ) {
			return;
		}

		$old_user = self::get_old_user();

		if ( $old_user instanceof WP_User ) {
			$link = sprintf(
			/* Translators: 1: user display name; 2: username; */
				__( 'Switch back to %1$s (%2$s)', 'miqid-core' ),
				$old_user->display_name,
				$old_user->user_login
			);
			$url  = add_query_arg( [
				'redirect_to' => urlencode( self::current_url() ),
			], self::switch_back_url( $old_user ) );
			printf(
				'<p id="user_switching_switch_on"><a href="%s">%s</a></p>',
				esc_url( $url ),
				esc_html( $link )
			);
		}
	}

	/**
	 * Adds a 'Switch back to {user}' link to the WordPress login screen.
	 *
	 * @param  string  $message  The login screen message.
	 *
	 * @return string The login screen message.
	 */
	public function filter_login_message( string $message ): string {
		$old_user = self::get_old_user();

		if ( $old_user instanceof WP_User ) {
			$link = sprintf(
			/* Translators: 1: user display name; 2: username; */
				__( 'Switch back to %1$s (%2$s)', 'miqid-core' ),
				$old_user->display_name,
				$old_user->user_login
			);
			$url  = self::switch_back_url( $old_user );

			if ( ! empty( $_REQUEST['interim-login'] ) ) {
				$url = add_query_arg( [
					'interim-login' => '1',
				], $url );
			} elseif ( ! empty( $_REQUEST['redirect_to'] ) ) {
				$url = add_query_arg( [
					'redirect_to' => urlencode( wp_unslash( $_REQUEST['redirect_to'] ) ),
				], $url );
			}

			$message .= '<p class="message" id="user_switching_switch_on">';
			$message .= '<span class="dashicons dashicons-admin-users" style="color:#56c234" aria-hidden="true"></span> ';
			$message .= sprintf(
				'<a href="%1$s" onclick="window.location.href=\'%1$s\';return false;">%2$s</a>',
				esc_url( $url ),
				esc_html( $link )
			);
			$message .= '</p>';
		}

		return $message;
	}

	/**
	 * Adds a 'Switch To' link to each member's profile page and profile listings in BuddyPress.
	 */
	public function action_bp_button() {
		$user = null;

		if ( bp_is_user() ) {
			$user = get_userdata( bp_displayed_user_id() );
		} elseif ( bp_is_members_directory() ) {
			$user = get_userdata( bp_get_member_user_id() );
		}

		if ( ! $user ) {
			return;
		}

		$link = self::maybe_switch_url( $user );

		if ( ! $link ) {
			return;
		}

		$link = add_query_arg( [
			'redirect_to' => urlencode( bp_core_get_user_domain( $user->ID ) ),
		], $link );

		$components = array_keys( buddypress()->active_components );

		echo bp_get_button( [
			'id'         => 'user_switching',
			'component'  => reset( $components ),
			'link_href'  => esc_url( $link ),
			'link_text'  => esc_html__( 'Switch&nbsp;To', 'miqid-core' ),
			'wrapper_id' => 'user_switching_switch_to',
		] );
	}

	/**
	 * Adds a 'Switch To' link to each member's profile page in bbPress.
	 */
	public function action_bbpress_button() {
		$user = get_userdata( bbp_get_user_id() );

		if ( ! $user ) {
			return;
		}

		$link = self::maybe_switch_url( $user );

		if ( ! $link ) {
			return;
		}

		$link = add_query_arg( [
			'redirect_to' => urlencode( bbp_get_user_profile_url( $user->ID ) ),
		], $link );

		echo '<ul id="user_switching_switch_to">';
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $link ),
			esc_html__( 'Switch&nbsp;To', 'miqid-core' )
		);
		echo '</ul>';
	}

	/**
	 * Filters the list of query arguments which get removed from admin area URLs in WordPress.
	 *
	 * @link https://core.trac.wordpress.org/ticket/23367
	 *
	 * @param  string[]  $args  Array of removable query arguments.
	 *
	 * @return string[] Updated array of removable query arguments.
	 */
	public function filter_removable_query_args( array $args ): array {
		return array_merge( $args, [
			'user_switched',
			'switched_off',
			'switched_back',
		] );
	}

	/**
	 * Returns the switch to or switch back URL for a given user.
	 *
	 * @param  WP_User  $user  The user to be switched to.
	 *
	 * @return string|false The required URL, or false if there's no old user or the user doesn't have the required capability.
	 */
	public function maybe_switch_url( WP_User $user ) {
		$old_user = self::get_old_user();

		if ( $old_user && ( $old_user->ID === $user->ID ) ) {
			return self::switch_back_url( $old_user );
		} elseif ( current_user_can( 'switch_to_user', $user->ID ) ) {
			return self::switch_to_url( $user );
		} else {
			return false;
		}
	}

	/**
	 * Returns the nonce-secured URL needed to switch to a given user ID.
	 *
	 * @param  WP_User  $user  The user to be switched to.
	 *
	 * @return string The required URL.
	 */
	public function switch_to_url( WP_User $user ): string {
		return wp_nonce_url( add_query_arg( [
			'action'  => 'switch_to_user',
			'user_id' => $user->ID,
			'nr'      => 1,
		], wp_login_url() ), "switch_to_user_{$user->ID}" );
	}

	/**
	 * Returns the nonce-secured URL needed to switch back to the originating user.
	 *
	 * @param  WP_User  $user  The old user.
	 *
	 * @return string        The required URL.
	 */
	public function switch_back_url( WP_User $user ): string {
		return wp_nonce_url( add_query_arg( [
			'action' => 'switch_to_olduser',
			'nr'     => 1,
		], wp_login_url() ), "switch_to_olduser_{$user->ID}" );
	}

	/**
	 * Returns the nonce-secured URL needed to switch off the current user.
	 *
	 * @param  WP_User  $user  The user to be switched off.
	 *
	 * @return string        The required URL.
	 */
	public function switch_off_url( WP_User $user ): string {
		return wp_nonce_url( add_query_arg( [
			'action' => 'switch_off',
			'nr'     => 1,
		], wp_login_url() ), "switch_off_{$user->ID}" );
	}

	/**
	 * Returns the current URL.
	 *
	 * @return string The current URL.
	 */
	public function current_url(): string {
		return ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Removes a list of common confirmation-style query args from a URL.
	 *
	 * @param  string  $url  A URL.
	 *
	 * @return string The URL with query args removed.
	 */
	public function remove_query_args( string $url ): string {
		if ( function_exists( 'wp_removable_query_args' ) ) {
			$url = remove_query_arg( wp_removable_query_args(), $url );
		}

		return $url;
	}

	/**
	 * Returns whether User Switching's equivalent of the 'logged_in' cookie should be secure.
	 *
	 * This is used to set the 'secure' flag on the old user cookie, for enhanced security.
	 *
	 * @link https://core.trac.wordpress.org/ticket/15330
	 *
	 * @return bool Should the old user cookie be secure?
	 */
	public function secure_olduser_cookie(): bool {
		return ( is_ssl() && ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) ) );
	}

	/**
	 * Returns whether User Switching's equivalent of the 'auth' cookie should be secure.
	 *
	 * This is used to determine whether to set a secure auth cookie.
	 *
	 * @return bool Whether the auth cookie should be secure.
	 */
	public function secure_auth_cookie(): bool {
		return ( is_ssl() && ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) ) );
	}

	/**
	 * Instructs WooCommerce to forget the session for the current user, without deleting it.
	 */
	public function forget_woocommerce_session() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$wc = WC();

		if ( ! property_exists( $wc, 'session' ) ) {
			return;
		}

		if ( ! method_exists( $wc->session, 'forget_session' ) ) {
			return;
		}

		$wc->session->forget_session();
	}

	/**
	 * Filters a user's capabilities so they can be altered at runtime.
	 *
	 * This is used to:
	 *  - Grant the 'switch_to_user' capability to the user if they have the ability to edit the user they're trying to
	 *    switch to (and that user is not themselves).
	 *  - Grant the 'switch_off' capability to the user if they can edit other users.
	 *
	 * Important: This does not get called for Super Admins. See filter_map_meta_cap() below.
	 *
	 * @param  bool[]  $user_caps  Array of key/value pairs where keys represent a capability name and boolean values
	 *                                represent whether the user has that capability.
	 * @param  string[]  $required_caps  Array of required primitive capabilities for the requested capability.
	 * @param  array  $args  {
	 *     Arguments that accompany the requested capability check.
	 *
	 * @type string    $0 Requested capability.
	 * @type int       $1 Concerned user ID.
	 * @type mixed  ...$2 Optional second and further parameters.
	 * }
	 *
	 * @param  WP_User  $user  Concerned user object.
	 *
	 * @return bool[] Array of concerned user's capabilities.
	 */
	public function filter_user_has_cap( array $user_caps, array $required_caps, array $args, WP_User $user ): array {
		if ( 'switch_to_user' === $args[0] ) {
			if ( empty( $args[2] ) ) {
				$user_caps['switch_to_user'] = false;

				return $user_caps;
			}
			if ( array_key_exists( 'switch_users', $user_caps ) ) {
				$user_caps['switch_to_user'] = $user_caps['switch_users'];

				return $user_caps;
			}

			$user_caps['switch_to_user'] = ( user_can( $user->ID, 'edit_user', $args[2] ) && ( $args[2] !== $user->ID ) );
		} elseif ( 'switch_off' === $args[0] ) {
			if ( array_key_exists( 'switch_users', $user_caps ) ) {
				$user_caps['switch_off'] = $user_caps['switch_users'];

				return $user_caps;
			}

			$user_caps['switch_off'] = user_can( $user->ID, 'edit_users' );
		}

		return $user_caps;
	}

	/**
	 * Filters the required primitive capabilities for the given primitive or meta capability.
	 *
	 * This is used to:
	 *  - Add the 'do_not_allow' capability to the list of required capabilities when a Super Admin is trying to switch
	 *    to themselves.
	 *
	 * It affects nothing else as Super Admins can do everything by default.
	 *
	 * @param  string[]  $required_caps  Array of required primitive capabilities for the requested capability.
	 * @param  string  $cap  Capability or meta capability being checked.
	 * @param  int  $user_id  Concerned user ID.
	 * @param  array  $args  {
	 *     Arguments that accompany the requested capability check.
	 *
	 * @return string[] Array of required capabilities for the requested action.
	 */
	public function filter_map_meta_cap( array $required_caps, string $cap, int $user_id, array $args ): array {
		if ( 'switch_to_user' === $cap ) {
			if ( empty( $args[0] ) || $args[0] === $user_id ) {
				$required_caps[] = 'do_not_allow';
			}
		}

		return $required_caps;
	}

	/**
	 * Sets authorisation cookies containing the originating user information.
	 *
	 * @param  int  $old_user_id  The ID of the originating user, usually the current logged in user.
	 * @param  bool  $pop  Optional. Pop the latest user off the auth cookie, instead of appending the new one. Default false.
	 * @param  string  $token  Optional. The old user's session token to store for later reuse. Default empty string.
	 *
	 * @since 1.4.0 The `$token` parameter was added.
	 */
	function user_switching_set_olduser_cookie( int $old_user_id, $pop = false, $token = '' ) {
		$secure_auth_cookie    = self::secure_auth_cookie();
		$secure_olduser_cookie = self::secure_olduser_cookie();
		$expiration            = time() + 172800; // 48 hours
		$auth_cookie           = self::user_switching_get_auth_cookie();
		$olduser_cookie        = wp_generate_auth_cookie( $old_user_id, $expiration, 'logged_in', $token );

		if ( $secure_auth_cookie ) {
			$auth_cookie_name = USER_SWITCHING_SECURE_COOKIE;
			$scheme           = 'secure_auth';
		} else {
			$auth_cookie_name = USER_SWITCHING_COOKIE;
			$scheme           = 'auth';
		}

		if ( $pop ) {
			array_pop( $auth_cookie );
		} else {
			array_push( $auth_cookie, wp_generate_auth_cookie( $old_user_id, $expiration, $scheme, $token ) );
		}

		$auth_cookie = json_encode( $auth_cookie );

		/**
		 * Fires immediately before the User Switching authentication cookie is set.
		 *
		 * @param  string  $auth_cookie  JSON-encoded array of authentication cookie values.
		 * @param  int  $expiration  The time when the authentication cookie expires as a UNIX timestamp.
		 * @param  int  $old_user_id  User ID.
		 * @param  string  $scheme  Authentication scheme. Values include 'auth' or 'secure_auth'.
		 * @param  string  $token  User's session token to use for the latest cookie.
		 *
		 * @since 1.4.0
		 *
		 */
		do_action( 'set_user_switching_cookie', $auth_cookie, $expiration, $old_user_id, $scheme, $token );

		$scheme = 'logged_in';

		/**
		 * Fires immediately before the User Switching old user cookie is set.
		 *
		 * @param  string  $olduser_cookie  The old user cookie value.
		 * @param  int  $expiration  The time when the logged-in authentication cookie expires as a UNIX timestamp.
		 * @param  int  $old_user_id  User ID.
		 * @param  string  $scheme  Authentication scheme. Values include 'auth' or 'secure_auth'.
		 * @param  string  $token  User's session token to use for this cookie.
		 *
		 * @since 1.4.0
		 *
		 */
		do_action( 'set_olduser_cookie', $olduser_cookie, $expiration, $old_user_id, $scheme, $token );

		/**
		 * Allows preventing auth cookies from actually being sent to the client.
		 *
		 * @param  bool  $send  Whether to send auth cookies to the client.
		 *
		 * @since 1.5.4
		 *
		 */
		if ( ! apply_filters( 'user_switching_send_auth_cookies', true ) ) {
			return;
		}

		setcookie( $auth_cookie_name, $auth_cookie, $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_auth_cookie, true );
		setcookie( USER_SWITCHING_OLDUSER_COOKIE, $olduser_cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN, $secure_olduser_cookie, true );
	}

	/**
	 * Clears the cookies containing the originating user, or pops the latest item off the end if there's more than one.
	 *
	 * @param  bool  $clear_all  Optional. Whether to clear the cookies (as opposed to just popping the last user off the end). Default true.
	 */
	function user_switching_clear_olduser_cookie( $clear_all = true ) {
		$auth_cookie = self::user_switching_get_auth_cookie();
		if ( ! empty( $auth_cookie ) ) {
			array_pop( $auth_cookie );
		}
		if ( $clear_all || empty( $auth_cookie ) ) {
			/**
			 * Fires just before the user switching cookies are cleared.
			 *
			 * @since 1.4.0
			 */
			do_action( 'clear_olduser_cookie' );

			/** This filter is documented in user-switching.php */
			if ( ! apply_filters( 'user_switching_send_auth_cookies', true ) ) {
				return;
			}

			$expire = time() - 31536000;
			setcookie( USER_SWITCHING_COOKIE, ' ', $expire, SITECOOKIEPATH, COOKIE_DOMAIN );
			setcookie( USER_SWITCHING_SECURE_COOKIE, ' ', $expire, SITECOOKIEPATH, COOKIE_DOMAIN );
			setcookie( USER_SWITCHING_OLDUSER_COOKIE, ' ', $expire, COOKIEPATH, COOKIE_DOMAIN );
		} else {
			if ( user_switching::secure_auth_cookie() ) {
				$scheme = 'secure_auth';
			} else {
				$scheme = 'auth';
			}

			$old_cookie = end( $auth_cookie );

			$old_user_id = wp_validate_auth_cookie( $old_cookie, $scheme );
			if ( $old_user_id ) {
				$parts = wp_parse_auth_cookie( $old_cookie, $scheme );
				self::user_switching_set_olduser_cookie( $old_user_id, true, $parts['token'] );
			}
		}
	}

	/**
	 * Gets the value of the cookie containing the originating user.
	 *
	 * @return string|false The old user cookie, or boolean false if there isn't one.
	 */
	function user_switching_get_olduser_cookie() {
		if ( isset( $_COOKIE[ USER_SWITCHING_OLDUSER_COOKIE ] ) ) {
			return wp_unslash( $_COOKIE[ USER_SWITCHING_OLDUSER_COOKIE ] );
		} else {
			return false;
		}
	}

	/**
	 * Gets the value of the auth cookie containing the list of originating users.
	 *
	 * @return string[] Array of originating user authentication cookie values. Empty array if there are none.
	 */
	function user_switching_get_auth_cookie(): array {
		if ( user_switching::secure_auth_cookie() ) {
			$auth_cookie_name = USER_SWITCHING_SECURE_COOKIE;
		} else {
			$auth_cookie_name = USER_SWITCHING_COOKIE;
		}

		if ( isset( $_COOKIE[ $auth_cookie_name ] ) && is_string( $_COOKIE[ $auth_cookie_name ] ) ) {
			$cookie = json_decode( wp_unslash( $_COOKIE[ $auth_cookie_name ] ) );
		}
		if ( ! isset( $cookie ) || ! is_array( $cookie ) ) {
			$cookie = [];
		}

		return $cookie;
	}

	/**
	 * Switches the current logged in user to the specified user.
	 *
	 * @param  int  $user_id  The ID of the user to switch to.
	 * @param  bool  $remember  Optional. Whether to 'remember' the user in the form of a persistent browser cookie. Default false.
	 * @param  bool  $set_old_user  Optional. Whether to set the old user cookie. Default true.
	 *
	 * @return false|WP_User WP_User object on success, false on failure.
	 */
	function switch_to_user( int $user_id, $remember = false, $set_old_user = true ) {
		$user = get_userdata( $user_id );

		if ( ! $user ) {
			return false;
		}

		$old_user_id  = ( is_user_logged_in() ) ? get_current_user_id() : false;
		$old_token    = function_exists( 'wp_get_session_token' ) ? wp_get_session_token() : '';
		$auth_cookie  = self::user_switching_get_auth_cookie();
		$cookie_parts = wp_parse_auth_cookie( end( $auth_cookie ) );

		if ( $set_old_user && $old_user_id ) {
			// Switching to another user
			$new_token = '';
			self::user_switching_set_olduser_cookie( $old_user_id, false, $old_token );
		} else {
			// Switching back, either after being switched off or after being switched to another user
			$new_token = $cookie_parts['token'] ?? '';
			self::user_switching_clear_olduser_cookie( false );
		}

		/**
		 * Attaches the original user ID and session token to the new session when a user switches to another user.
		 *
		 * @param  array  $session  Array of extra data.
		 * @param  int  $user_id  User ID.
		 *
		 * @return array Array of extra data.
		 */
		$session_filter = function ( array $session, int $user_id ) use ( $old_user_id, $old_token ) {
			$session['switched_from_id']      = $old_user_id;
			$session['switched_from_session'] = $old_token;

			return $session;
		};

		add_filter( 'attach_session_information', $session_filter, 99, 2 );

		wp_clear_auth_cookie();
		wp_set_auth_cookie( $user_id, $remember, '', $new_token );
		wp_set_current_user( $user_id );

		remove_filter( 'attach_session_information', $session_filter, 99 );

		if ( $set_old_user ) {
			/**
			 * Fires when a user switches to another user account.
			 *
			 * @param  int  $user_id  The ID of the user being switched to.
			 * @param  int  $old_user_id  The ID of the user being switched from.
			 * @param  string  $new_token  The token of the session of the user being switched to. Can be an empty string
			 *                            or a token for a session that may or may not still be valid.
			 * @param  string  $old_token  The token of the session of the user being switched from.
			 *
			 * @since 0.6.0
			 * @since 1.4.0 The `$new_token` and `$old_token` parameters were added.
			 *
			 */
			do_action( 'switch_to_user', $user_id, $old_user_id, $new_token, $old_token );
		} else {
			/**
			 * Fires when a user switches back to their originating account.
			 *
			 * @param  int  $user_id  The ID of the user being switched back to.
			 * @param  int|false  $old_user_id  The ID of the user being switched from, or false if the user is switching back
			 *                               after having been switched off.
			 * @param  string  $new_token  The token of the session of the user being switched to. Can be an empty string
			 *                               or a token for a session that may or may not still be valid.
			 * @param  string  $old_token  The token of the session of the user being switched from.
			 *
			 * @since 0.6.0
			 * @since 1.4.0 The `$new_token` and `$old_token` parameters were added.
			 *
			 */
			do_action( 'switch_back_user', $user_id, $old_user_id, $new_token, $old_token );
		}

		if ( $old_token && $old_user_id && ! $set_old_user ) {
			// When switching back, destroy the session for the old user
			$manager = WP_Session_Tokens::get_instance( $old_user_id );
			$manager->destroy( $old_token );
		}

		return $user;
	}

	/**
	 * Switches off the current logged in user. This logs the current user out while retaining a cookie allowing them to log
	 * straight back in using the 'Switch back to {user}' system.
	 *
	 * @return bool True on success, false on failure.
	 */
	function switch_off_user(): bool {
		$old_user_id = get_current_user_id();

		if ( ! $old_user_id ) {
			return false;
		}

		$old_token = function_exists( 'wp_get_session_token' ) ? wp_get_session_token() : '';

		self::user_switching_set_olduser_cookie( $old_user_id, false, $old_token );
		wp_clear_auth_cookie();
		wp_set_current_user( 0 );

		/**
		 * Fires when a user switches off.
		 *
		 * @param  int  $old_user_id  The ID of the user switching off.
		 * @param  string  $old_token  The token of the session of the user switching off.
		 *
		 * @since 0.6.0
		 * @since 1.4.0 The `$old_token` parameter was added.
		 *
		 */
		do_action( 'switch_off_user', $old_user_id, $old_token );

		return true;
	}

	/**
	 * Returns whether the current user switched into their account.
	 *
	 * @return false|WP_User False if the user isn't logged in or they didn't switch in; old user object (which evaluates to
	 *                       true) if the user switched into the current user account.
	 */
	function current_user_switched() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		return self::get_old_user();
	}
}
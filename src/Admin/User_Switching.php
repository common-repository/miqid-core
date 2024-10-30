<?php

namespace MIQID\Plugin\Core\Admin;

use WP_User;

class User_Switching extends \MIQID\Plugin\Core\Frontend\User_Switching {

	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {
		add_action( 'all_admin_notices', [ $this, '_notices' ], 1 );
		add_filter( 'user_row_actions', [ $this, 'filter_user_row_actions' ], 10, 2 );
		add_filter( 'ms_user_row_actions', [ $this, 'filter_user_row_actions' ], 10, 2 );
	}

	/**
	 * Displays the 'Switched to {user}' and 'Switch back to {user}' messages in the admin area.
	 */
	public function _notices() {
		$user     = wp_get_current_user();
		$old_user = self::get_old_user();

		if ( $old_user ) {
			$switched_locale = false;
			$lang_attr       = '';

			if ( function_exists( 'get_user_locale' ) ) {
				$locale          = get_user_locale( $old_user );
				$switched_locale = switch_to_locale( $locale );
				$lang_attr       = str_replace( '_', '-', $locale );
			}

			?>
            <div id="user_switching" class="updated notice is-dismissible">
				<?php
				if ( $lang_attr ) {
					printf(
						'<p lang="%s">',
						esc_attr( $lang_attr )
					);
				} else {
					echo '<p>';
				}
				?>
                <span class="dashicons dashicons-admin-users" style="color:#56c234" aria-hidden="true"></span>
				<?php
				$message       = '';
				$just_switched = isset( $_GET['user_switched'] );
				if ( $just_switched ) {
					$message = esc_html( sprintf(
					/* Translators: 1: user display name; 2: username; */
						__( 'Switched to %1$s (%2$s).', 'user-switching' ),
						$user->display_name,
						$user->user_login
					) );
				}
				$switch_back_url = add_query_arg( [
					'redirect_to' => urlencode( self::current_url() ),
				], self::switch_back_url( $old_user ) );

				$message .= sprintf(
					' <a href="%s">%s</a>.',
					esc_url( $switch_back_url ),
					esc_html( sprintf(
					/* Translators: 1: user display name; 2: username; */
						__( 'Switch back to %1$s (%2$s)', 'user-switching' ),
						$old_user->display_name,
						$old_user->user_login
					) )
				);

				/**
				 * Filters the contents of the message that's displayed to switched users in the admin area.
				 *
				 * @param string $message The message displayed to the switched user.
				 * @param WP_User $user The current user object.
				 * @param WP_User $old_user The old user object.
				 * @param string $switch_back_url The switch back URL.
				 * @param bool $just_switched Whether the user made the switch on this page request.
				 *
				 * @since 1.1.0
				 *
				 */
				$message = apply_filters( 'user_switching_switched_message', $message, $user, $old_user, $switch_back_url, $just_switched );

				echo wp_kses( $message, [
					'a' => [
						'href' => [],
					],
				] );
				print '</p>';
				?>
            </div>
			<?php
			if ( $switched_locale ) {
				restore_previous_locale();
			}
		} else if ( isset( $_GET['user_switched'] ) ) {
			?>
            <div id="user_switching" class="updated notice is-dismissible">
                <p>
					<?php
					if ( isset( $_GET['switched_back'] ) ) {
						echo esc_html( sprintf(
						/* Translators: 1: user display name; 2: username; */
							__( 'Switched back to %1$s (%2$s).', 'user-switching' ),
							$user->display_name,
							$user->user_login
						) );
					} else {
						echo esc_html( sprintf(
						/* Translators: 1: user display name; 2: username; */
							__( 'Switched to %1$s (%2$s).', 'user-switching' ),
							$user->display_name,
							$user->user_login
						) );
					}
					?>
                </p>
            </div>
			<?php
		}
	}

	/**
	 * Adds a 'Switch To' link to each list of user actions on the Users screen.
	 *
	 * @param string[] $actions Array of actions to display for this user row.
	 * @param WP_User $user The user object displayed in this row.
	 *
	 * @return string[] Array of actions to display for this user row.
	 */
	public function filter_user_row_actions( array $actions, WP_User $user ): array {
		$link = self::maybe_switch_url( $user );

		if ( ! $link ) {
			return $actions;
		}

		$actions['switch_to_user'] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $link ),
			esc_html__( 'Switch To', 'miqid-core' )
		);

		return $actions;
	}
}
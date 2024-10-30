<?php

namespace MIQID\Plugin\Core;

use DateTime;
use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, JWT};
use MIQID\Plugin\Core\Classes\Settings;
use ReflectionClass;
use ReflectionException;

class Util {
	static function id( ...$id ): string {
		return mb_strtolower( implode( '_', $id ) );
	}

	/**
	 * @return JWT
	 */
	public static function get_user_jwt(): JWT {
		if ( ( $user_id = get_current_user_id() ) && ( $JWT = get_user_meta( $user_id, 'miqid_jwt', true ) ) ) {
			if ( is_array( $JWT ) ) {
				return new JWT( $JWT );
			} else if ( $JWT instanceof JWT ) {
				return $JWT;
			}
		}

		return new JWT();
	}

	/**
	 * @param JWT|HttpResponse $JWT
	 */
	public static function update_user_jwt( $JWT ) {
		if ( ( $user = wp_get_current_user() ) && $JWT instanceof JWT ) {
			update_user_meta( $user->ID, 'miqid_jwt', $JWT->jsonSerialize() );
		}
	}

	public static function get_assets_css_url(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_url(), 'assets/css' );
	}

	public static function get_assets_images_url(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_url(), 'assets/images' );
	}

	public static function get_assets_js_url(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_url(), 'assets/js' );
	}

	public static function get_assets_css_path(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_path(), 'assets/css' );
	}

	public static function get_assets_images_path(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_path(), 'assets/images' );
	}

	public static function get_assets_js_path(): string {
		return sprintf( '%s/%s', self::get_plugin_dir_path(), 'assets/js' );
	}

	public static function get_plugin_dir_url(): string {
		return plugins_url( '', __DIR__ );
	}

	public static function get_plugin_dir_path(): string {
		return dirname( __DIR__ );
	}

	public static function snake_case( $str ): string {
		$arr = preg_split( '/([A-Za-z][a-z]+|\d+|_+|-+)/', $str, - 1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
		$arr = array_filter( $arr, function ( $str ) {
			return trim( $str, " \t\n\r\0\x0B\_" );
		} );

		return mb_strtolower( implode( '_', $arr ) );
	}

	/** @return Settings */
	public static function get_miqid_core_settings(): Settings {
		$feature = get_option( 'miqid-core', [] );
		if ( $feature instanceof Settings ) {
			return $feature;
		}

		return new Settings( $feature );
	}

	public static function get_case_dir(): string {
		return implode( DIRECTORY_SEPARATOR, [
			wp_upload_dir()['basedir'],
			'miqid-cache',
		] );
	}

	public static function clean_cache( $folder, $lifetime ) {
		foreach ( glob( sprintf( '%s/*', $folder ) ) as $file ) {
			if ( ( new DateTime() )->diff( ( new DateTime() )->setTimestamp( filemtime( $file ) ) )->i > $lifetime ) {
				unlink( $file );
			}
		}
	}

	/**
	 * @param string $class
	 * @param string $function
	 * @param string|null $profileId
	 * @param null $json
	 *
	 * @return array|null
	 * @throws ReflectionException
	 */
	public static function cache_handler( string $class, string $function, ?string $profileId, $json = null ): ?array {
		if ( class_exists( $class )
		     && $function
		     && $profileId ) {
			$ReflectionClass = new ReflectionClass( $class );
			$basedir         = wp_upload_dir()['basedir'];
			$filename        = implode( DIRECTORY_SEPARATOR, [
				$basedir,
				'miqid-cache',
				$ReflectionClass->getShortName(),
				$function,
				sprintf( '%s.json', $profileId ),
			] );

			if ( ( $dirname = dirname( $filename ) ) && ! file_exists( $dirname ) ) {
				wp_mkdir_p( $dirname );
			}

			if ( isset( $json ) ) {
				file_put_contents( $filename, $json );
			}

			if ( file_exists( $filename ) ) {
				return json_decode( file_get_contents( $filename ), true );
			}

			register_shutdown_function( function () use ( $filename ) {
				@unlink( $filename );
			} );
		}

		return null;
	}

	static function get_profileId(): ?string {
		$req = array_change_key_case( $_REQUEST, CASE_LOWER );

		if ( isset( $req['profileid'] ) ) {
			return $req['profileid'];
		} else if ( isset( $req['userid'] ) ) {
			return $req['userid'];
		} else if ( isset( $_COOKIE['miqid_profile'] ) ) {
			return $_COOKIE['miqid_profile'];
		}

		return self::get_user_jwt()->get_jwt_payload()->get_profile_id();
	}
}
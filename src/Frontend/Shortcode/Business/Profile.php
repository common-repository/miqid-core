<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\{Business\Profile as dtoProfile, HttpResponse};
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Profile extends \MIQID\Plugin\Core\Classes\API\Business\Profile {
	private static $instance;
	/** @var dtoProfile[] */
	private $profiles = [];

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-business-profile', [ $this, 'Shortcode' ] );
		add_shortcode( 'miqid-companyprofile', [ $this, 'Shortcode' ] );
	}

	public function GetProfile( $profileId ) {
		if ( isset( $this->profiles[ $profileId ] ) ) {
			return $this->profiles[ $profileId ];
		}
		if ( ( $profile = parent::GetProfile( $profileId ) ) && $profile instanceof HttpResponse ) {
			$profile = new dtoProfile();
		}

		return $this->profiles[ $profileId ] = $profile;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [
			'profileid' => Util::get_profileId(),
			'fields'    => '',
			'separator' => ' ',
		], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoProfile::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetProfile( $atts['profileid'] ) );

		if ( in_array( $atts['fields'], [ 'business-passportfaceimage', 'business-passport' ] ) ) {
			$output = [ do_shortcode( sprintf( '[miqid-%s]', $atts['fields'] ) ) ];
		}

		return implode( $atts['separator'], $output );
	}
}
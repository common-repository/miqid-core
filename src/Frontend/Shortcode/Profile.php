<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Classes\DTO\{HttpResponse, Profile as dtoProfile};
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Profile extends \MIQID\Plugin\Core\Classes\API\Profile {
	private static $instance;
	private        $profile;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-profile', [ $this, 'Shortcode' ] );
	}

	public function GetProfile() {
		if ( isset( $this->profile ) ) {
			return $this->profile;
		}

		if ( ( $profile = parent::GetProfile() ) && $profile instanceof HttpResponse ) {
			$profile = new dtoProfile();
		}

		return $this->profile = $profile;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [ 'fields' => '', 'separator' => ' ' ], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoProfile::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetProfile() );

		return implode( $atts['separator'], $output );
	}
}
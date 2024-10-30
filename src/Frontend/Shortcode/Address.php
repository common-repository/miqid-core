<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Classes\DTO\{Address as dtoAddress, HttpResponse};
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Address extends \MIQID\Plugin\Core\Classes\API\Address {
	private static $instance;
	private        $address;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-address', [ $this, 'Shortcode' ] );
	}

	public function GetPrimaryAddress() {
		if ( isset( $this->address ) ) {
			return $this->address;
		}

		if ( ( $address = parent::GetPrimaryAddress() ) && $address instanceof HttpResponse ) {
			$address = new dtoAddress();
		}

		return $this->address = $address;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [ 'fields' => '', 'separator' => ' ' ], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoAddress::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetPrimaryAddress() );

		return implode( $atts['separator'], $output );
	}
}
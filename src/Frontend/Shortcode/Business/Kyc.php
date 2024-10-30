<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\Business\Kyc as dtoKyc;
use MIQID\Plugin\Core\Classes\DTO\HttpResponse;
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Kyc extends \MIQID\Plugin\Core\Classes\API\Business\Kyc {
	private static $instance;
	/** @var dtoKyc[] */
	private $kycs = [];

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-business-kyc', [ $this, 'Shortcode' ] );
	}

	public function GetKyc( $profileId ) {
		if ( isset( $this->kycs[ $profileId ] ) ) {
			return $this->kycs[ $profileId ];
		}
		if ( ( $kyc = parent::GetKyc( $profileId ) ) && $kyc instanceof HttpResponse ) {
			$kyc = new dtoKyc();
		}

		return $this->kycs[ $profileId ] = $kyc;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [
			'profileid' => Util::get_profileId(),
			'fields'    => '',
			'separator' => ' ',
		], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoKyc::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetKyc( $atts['profileid'] ) );

		return implode( $atts['separator'], $output );
	}
}
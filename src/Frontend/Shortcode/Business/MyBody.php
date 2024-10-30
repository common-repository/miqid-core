<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\{Business\MyBody as dtoMyBody, HttpResponse};
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class MyBody extends \MIQID\Plugin\Core\Classes\API\Business\MyBody {
	private static $instance;
	/** @var dtoMyBody[] */
	private $Data = [];

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-business-mybody', [ $this, 'Shortcode' ] );
	}

	public function GetMyBody( $profileId ) {
		if ( isset( $this->Data[ $profileId ] ) ) {
			return $this->Data[ $profileId ];
		}
		if ( ( $obj = parent::GetMyBody( $profileId ) ) && $obj instanceof HttpResponse ) {
			$obj = new dtoMyBody();
		}

		return $this->Data[ $profileId ] = $obj;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [
			'profileid' => Util::get_profileId(),
			'fields'    => '',
			'separator' => ' ',
		], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoMyBody::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetMyBody( $atts['profileid'] ) );

		return implode( $atts['separator'], $output );
	}
}
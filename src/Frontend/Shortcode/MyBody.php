<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Classes\DTO\HttpResponse;
use MIQID\Plugin\Core\Classes\DTO\MyBody as dtoMyBody;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class MyBody extends \MIQID\Plugin\Core\Classes\API\MyBody {
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
		add_shortcode( 'miqid-mybody', [ $this, 'Shortcode' ] );
	}

	public function GetMyBody() {
		if ( isset( $this->profile ) ) {
			return $this->profile;
		}

		if ( ( $obj = parent::GetMyBody() ) && $obj instanceof HttpResponse ) {
			$obj = new dtoMyBody();
		}

		return $this->profile = $obj;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [ 'fields' => '', 'separator' => ' ' ], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoMyBody::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetMyBody() );

		return implode( $atts['separator'], $output );
	}
}
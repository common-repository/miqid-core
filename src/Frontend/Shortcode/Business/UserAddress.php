<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\Business\UserAddress as dtoUserAddress;
use MIQID\Plugin\Core\Classes\DTO\HttpResponse;
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class UserAddress extends \MIQID\Plugin\Core\Classes\API\Business\UserAddress {
	private static $instance;
	/** @var dtoUserAddress[] */
	private $userAddresses = [];

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-business-useraddress', [ $this, 'Shortcode' ] );
	}

	public function GetUserAddress( $profileId ) {
		if ( isset( $this->userAddresses[ $profileId ] ) ) {
			return $this->userAddresses[ $profileId ];
		}
		if ( ( $userAddress = parent::GetUserAddress( $profileId ) ) && $userAddress instanceof HttpResponse ) {
			$userAddress = new dtoUserAddress();
		}

		return $this->userAddresses[ $profileId ] = $userAddress;
	}

	function Shortcode( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [
			'profileid' => Util::get_profileId(),
			'fields'    => '',
			'separator' => ' ',
		], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoUserAddress::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $atts, $fields, $this->GetUserAddress( $atts['profileid'] ) );

		return implode( $atts['separator'], $output );
	}
}
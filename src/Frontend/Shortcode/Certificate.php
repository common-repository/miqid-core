<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use MIQID\Plugin\Core\Classes\DTO\{DriversLicense as dtoDriversLicense, HttpResponse, Passport as dtoPassport};
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Certificate extends \MIQID\Plugin\Core\Classes\API\Certificate {
	private static $instance;
	private        $passportCertificateInformation;
	private        $driversLicense;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/** @noinspection PhpMissingParentConstructorInspection */
	private function __construct() {
		add_shortcode( 'miqid-passport', [ $this, 'Shortcode_Passport' ] );
		add_shortcode( 'miqid-driverslicense', [ $this, 'Shortcode_DriversLicense' ] );
	}

	public function GetPassportCertificateInformation() {
		if ( isset( $this->passportCertificateInformation ) ) {
			return $this->passportCertificateInformation;
		}

		if ( ( $PassportCertificateInformation = parent::GetPassportCertificateInformation() ) && $PassportCertificateInformation instanceof HttpResponse ) {
			$PassportCertificateInformation = new dtoPassport();
		}

		return $this->passportCertificateInformation = $PassportCertificateInformation;
	}

	public function GetDriversLicenseCertificateInformation() {
		if ( isset( $this->driversLicense ) ) {
			return $this->driversLicense;
		}

		if ( ( $driversLicense = parent::GetDriversLicenseCertificateInformation() ) && $driversLicense instanceof HttpResponse ) {
			$driversLicense = new dtoDriversLicense();
		}

		return $this->driversLicense = $driversLicense;
	}

	function Shortcode_Passport( $atts ): string {

		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [ 'fields' => '', 'separator' => ' ' ], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoPassport::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		return implode( $atts['separator'],
			Shortcode::Shortcode_Output( $atts, $fields, $this->GetPassportCertificateInformation() ) );
	}

	function Shortcode_DriversLicense( $atts ): string {
		$atts   = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts   = shortcode_atts( [ 'fields' => '', 'separator' => ' ' ], $atts );
		$fields = [];

		$reflectionClass = new ReflectionClass( dtoDriversLicense::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		return implode( $atts['separator'],
			Shortcode::Shortcode_Output( $atts, $fields, $this->GetDriversLicenseCertificateInformation() ) );
	}

}
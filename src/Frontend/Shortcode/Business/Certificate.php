<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode\Business;

use MIQID\Plugin\Core\Classes\DTO\{Business\DriversLicense, Business\HealthInsuranceCard, Business\Passport, HttpResponse};
use MIQID\Plugin\Core\Frontend\Shortcode\Shortcode;
use MIQID\Plugin\Core\Util;
use ReflectionClass;

class Certificate extends \MIQID\Plugin\Core\Classes\API\Business\Certificate {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'miqid-business-passportimage', [ $this, 'Shortcode_PassportImage' ] );
		/** @deprecated */
		add_shortcode( 'miqid-profilepassportfaceimage', [ $this, 'Shortcode_PassportFaceImage' ] );
		add_shortcode( 'miqid-business-passportfaceimage', [ $this, 'Shortcode_PassportFaceImage' ] );
		add_shortcode( 'miqid-business-passport', [ $this, 'Shortcode_PassportCertificateInformation' ] );

		add_shortcode( 'miqid-business-driverslicensefaceimage', [ $this, 'Shortcode_DriversLicenseFaceImage' ] );
		add_shortcode( 'miqid-business-driverslicenseimage', [ $this, 'Shortcode_DriversLicenseImage' ] );
		add_shortcode( 'miqid-business-driverslicense', [ $this, 'Shortcode_DriversLicenseCertificateInformation' ] );

		add_shortcode( 'miqid-business-healthinsurancecardimage', [ $this, 'Shortcode_HealthInsuranceCardImage' ] );
		add_shortcode( 'miqid-business-healthinsurancecard', [ $this, 'Shortcode_HealthInsuranceCardCertificateInformation' ] );
	}

	// <editor-fold desc="Passport">
	private $ProfilePassportImage = [];
	private $ProfilePassportFaceImage = [];
	private $PassportCertificateInformation = [];

	public function GetProfilePassportImage( $profileId ) {
		if ( isset( $this->ProfilePassportImage[ $profileId ] ) ) {
			return $this->ProfilePassportImage[ $profileId ];
		}

		return $this->ProfilePassportImage[ $profileId ] = parent::GetProfilePassportImage( $profileId );
	}

	public function GetProfilePassportFaceImage( $profileId ) {
		if ( isset( $this->ProfilePassportFaceImage[ $profileId ] ) ) {
			return $this->ProfilePassportFaceImage[ $profileId ];
		}

		$ProfilePassportFaceImage = parent::GetProfilePassportFaceImage( $profileId );

		return $this->ProfilePassportFaceImage[ $profileId ] = $ProfilePassportFaceImage;
	}

	public function GetPassportCertificateInformation( $profileId ) {
		if ( isset( $this->PassportCertificateInformation[ $profileId ] ) ) {
			return $this->PassportCertificateInformation[ $profileId ];
		}
		if ( ( $PassportCertificateInformation = parent::GetPassportCertificateInformation( $profileId ) )
		     && $PassportCertificateInformation instanceof HttpResponse ) {
			$PassportCertificateInformation = new \MIQID\Plugin\Core\Classes\DTO\Passport();
		}

		return $this->PassportCertificateInformation[ $profileId ] = $PassportCertificateInformation;
	}

	function Shortcode_PassportImage( $attr ): string {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr = shortcode_atts( [ 'profileid' => Util::get_profileId() ], $attr );

		return Shortcode::Shortcode_Output_Image(
			$attr,
			$this->GetProfilePassportImage( $attr['profileid'] )
		);
	}

	function Shortcode_PassportFaceImage( $attr ): string {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr = shortcode_atts( [ 'profileid' => Util::get_profileId() ], $attr );

		return Shortcode::Shortcode_Output_Image(
			$attr,
			$this->GetProfilePassportFaceImage( $attr['profileid'] )
		);
	}

	function Shortcode_PassportCertificateInformation( $attr ): string {
		$attr   = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr   = shortcode_atts( [ 'profileid' => Util::get_profileId(), 'fields' => '', 'separator' => ' ' ], $attr );
		$fields = [];

		$reflectionClass = new ReflectionClass( Passport::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		if ( in_array( $attr['fields'], [ 'passportimage', 'passportfaceimage' ] ) ) {
			return do_shortcode( sprintf( '[miqid-business-%s]', $attr['fields'] ) );
		}

		return implode( $attr['separator'],
			Shortcode::Shortcode_Output( $attr, $fields, $this->GetPassportCertificateInformation( $attr['profileid'] ) ) );
	}

	// </editor-fold>

	// <editor-fold desc="Drivers License">
	private $ProfileDriversLicenseImage = [];
	private $DriversLicenseFaceImage = [];
	private $DriversLicenseCertificateInformation = [];

	public function GetProfileDriversLicenseImage( $profileId ) {
		if ( isset( $this->ProfileDriversLicenseImage[ $profileId ] ) ) {
			return $this->ProfileDriversLicenseImage[ $profileId ];
		}

		return $this->ProfileDriversLicenseImage[ $profileId ] = parent::GetProfileDriversLicenseImage( $profileId );
	}

	public function GetDriversLicenseFaceImage( $profileId ) {
		if ( isset( $this->DriversLicenseFaceImage[ $profileId ] ) ) {
			return $this->DriversLicenseFaceImage[ $profileId ];
		}

		return $this->DriversLicenseFaceImage[ $profileId ] = parent::GetDriversLicenseFaceImage( $profileId );
	}

	public function GetDriversLicenseCertificateInformation( $profileId ) {
		if ( isset( $this->DriversLicenseCertificateInformation[ $profileId ] ) ) {
			return $this->DriversLicenseCertificateInformation[ $profileId ];
		}
		if ( ( $DriversLicenseCertificateInformation = parent::GetDriversLicenseCertificateInformation( $profileId ) )
		     && $DriversLicenseCertificateInformation instanceof HttpResponse ) {
			$DriversLicenseCertificateInformation = new DriversLicense();
		}

		return $this->DriversLicenseCertificateInformation[ $profileId ] = $DriversLicenseCertificateInformation;
	}

	function Shortcode_DriversLicenseImage( $attr ): string {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr = shortcode_atts( [ 'profileid' => Util::get_profileId() ], $attr );

		return Shortcode::Shortcode_Output_Image(
			$attr,
			$this->GetProfileDriversLicenseImage( $attr['profileid'] )
		);
	}

	function Shortcode_DriversLicenseFaceImage( $attr ): string {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr = shortcode_atts( [ 'profileid' => Util::get_profileId() ], $attr );

		return Shortcode::Shortcode_Output_Image(
			$attr,
			$this->GetDriversLicenseFaceImage( $attr['profileid'] )
		);
	}

	function Shortcode_DriversLicenseCertificateInformation( $attr ): string {
		$attr   = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr   = shortcode_atts( [ 'profileid' => Util::get_profileId(), 'fields' => '', 'separator' => ' ' ], $attr );
		$fields = [];

		$reflectionClass = new ReflectionClass( DriversLicense::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		$output = Shortcode::Shortcode_Output( $attr, $fields, $this->GetDriversLicenseCertificateInformation( $attr['profileid'] ) );

		if ( in_array( $attr['fields'], [ 'driverslicensefaceimage', 'driverslicenseimage' ] ) ) {
			$output = [ do_shortcode( sprintf( '[miqid-business-%s]', $attr['fields'] ) ) ];
		}

		return implode( $attr['separator'], $output );
	}

	// </editor-fold>

	// <editor-fold desc="HealthInsuranceCard">
	private $HealthInsuranceCardImage = [];
	private $HealthInsuranceCardCertificateInformation = [];

	public function GetHealthInsuranceCardImage( $profileId ) {
		if ( isset( $this->HealthInsuranceCardImage[ $profileId ] ) ) {
			return $this->HealthInsuranceCardImage[ $profileId ];
		}

		return $this->HealthInsuranceCardImage[ $profileId ] = parent::GetHealthInsuranceCardImage( $profileId );
	}

	public function GetHealthInsuranceCardCertificateInformation( $profileId ) {
		if ( isset( $this->HealthInsuranceCardCertificateInformation[ $profileId ] ) ) {
			return $this->HealthInsuranceCardCertificateInformation[ $profileId ];
		}

		if ( $HealthInsuranceCardCertificateInformation = parent::GetHealthInsuranceCardCertificateInformation( $profileId ) ) {
			if ( $HealthInsuranceCardCertificateInformation instanceof HttpResponse ) {
				$HealthInsuranceCardCertificateInformation = new HealthInsuranceCard();
			}
		}

		return $this->HealthInsuranceCardCertificateInformation[ $profileId ] = $HealthInsuranceCardCertificateInformation;
	}

	function Shortcode_HealthInsuranceCardImage( $attr ): string {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr = shortcode_atts( [ 'profileid' => Util::get_profileId(), 'fields' => '', 'separator' => ' ' ], $attr );

		return Shortcode::Shortcode_Output_Image(
			$attr,
			$this->GetHealthInsuranceCardImage( $attr['profileid'] )
		);
	}

	function Shortcode_HealthInsuranceCardCertificateInformation( $attr ): string {
		$attr   = array_change_key_case( (array) $attr, CASE_LOWER );
		$attr   = shortcode_atts( [ 'profileid' => Util::get_profileId(), 'fields' => '', 'separator' => ' ' ], $attr );
		$fields = [];

		$reflectionClass = new ReflectionClass( HealthInsuranceCard::class );
		do {
			foreach ( $reflectionClass->getProperties() as $property ) {
				$fields[ $property->getName() ] = Util::snake_case( $property->getName() );
			}
		} while ( $reflectionClass = $reflectionClass->getParentClass() );

		if ( in_array( $attr['fields'], [ 'healthinsurancecardimage' ] ) ) {
			return do_shortcode( sprintf( '[miqid-business-%s]', $attr['fields'] ) );
		}

		return implode( $attr['separator'],
			Shortcode::Shortcode_Output(
				$attr,
				$fields,
				$this->GetHealthInsuranceCardCertificateInformation( $attr['profileid'] ) ) );
	}

	// </editor-fold>
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Enum\PermissionType};

class CertificateSettings extends SettingsBase {
	/** @var PermissionType */
	private $Passport;
	/** @var PermissionType */
	private $DriversLicence;
	/** @var PermissionType */
	private $HealthInsurance;
	/** @var PermissionType */
	private $SocialSecurityNumerCertificate;
	/** @var PermissionType */
	private $BaptismalCertificate;
	/** @var PermissionType */
	private $Diploma;

	public function __construct( ?array $CertificateSettings = null ) {
		if ( is_array( $CertificateSettings ) ) {
			$this->parse_array( $CertificateSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $Passport
	 *
	 * @return self
	 */
	public function set_passport( $Passport ): self {
		if ( is_string( $Passport ) ) {
			$Passport = PermissionType::$Passport();
		}
		$this->Passport = new PermissionType( $Passport );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_passport(): PermissionType {
		return $this->Passport;
	}

	/**
	 * @param PermissionType|int|string $DriversLicence
	 *
	 * @return self
	 */
	public function set_drivers_licence( $DriversLicence ): self {
		if ( is_string( $DriversLicence ) ) {
			$DriversLicence = PermissionType::$DriversLicence();
		}
		$this->DriversLicence = new PermissionType( $DriversLicence );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_drivers_licence(): PermissionType {
		return $this->DriversLicence;
	}

	/**
	 * @param PermissionType|int|string $HealthInsurance
	 *
	 * @return self
	 */
	public function set_health_insurance( $HealthInsurance ): self {
		if ( is_string( $HealthInsurance ) ) {
			$HealthInsurance = PermissionType::$HealthInsurance();
		}
		$this->HealthInsurance = new PermissionType( $HealthInsurance );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_health_insurance(): PermissionType {
		return $this->HealthInsurance;
	}

	/**
	 * @param PermissionType|int|string $SocialSecurityNumerCertificate
	 *
	 * @return self
	 */
	public function set_social_security_numer_certificate( $SocialSecurityNumerCertificate ): self {
		if ( is_string( $SocialSecurityNumerCertificate ) ) {
			$SocialSecurityNumerCertificate = PermissionType::$SocialSecurityNumerCertificate();
		}
		$this->SocialSecurityNumerCertificate = new PermissionType( $SocialSecurityNumerCertificate );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_social_security_numer_certificate(): PermissionType {
		return $this->SocialSecurityNumerCertificate;
	}

	/**
	 * @param PermissionType|int|string $BaptismalCertificate
	 *
	 * @return self
	 */
	public function set_baptismal_certificate( $BaptismalCertificate ): self {
		if ( is_string( $BaptismalCertificate ) ) {
			$BaptismalCertificate = PermissionType::$BaptismalCertificate ();
		}
		$this->BaptismalCertificate = new PermissionType( $BaptismalCertificate );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_baptismal_certificate(): PermissionType {
		return $this->BaptismalCertificate;
	}

	/**
	 * @param PermissionType|int|string $Diploma
	 *
	 * @return self
	 */
	public function set_diploma( $Diploma ): self {
		if ( is_string( $Diploma ) ) {
			$Diploma = PermissionType::$Diploma();
		}
		$this->Diploma = new PermissionType( $Diploma );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_diploma(): PermissionType {
		return $this->Diploma;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
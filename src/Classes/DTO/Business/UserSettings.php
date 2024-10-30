<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Base;

class UserSettings extends Base {
	/** @var AppearanceSettings */
	private $AppearanceSettings;
	/** @var BiometricsSettings */
	private $BiometricsSettings;
	/** @var CertificateSettings */
	private $CertificateSettings;
	/** @var CommerceSettings */
	private $CommerceSettings;
	/** @var InterestsSettings */
	private $InterestsSettings;
	/** @var LoginSettings */
	private $LoginSettings;
	/** @var MyBodySettings */
	private $MyBodySettings;
	/** @var PlacesSettings */
	private $PlacesSettings;
	/** @var ProfileSettings */
	private $ProfileSettings;

	public function __construct( ?array $UserSettings = null ) {
		if ( is_array( $UserSettings ) ) {
			$this->parse_array( $UserSettings );
		}
	}

	/**
	 * @param AppearanceSettings|array|null $AppearanceSettings
	 */
	public function set_appearance_settings( $AppearanceSettings ): void {
		if ( is_array( $AppearanceSettings ) ) {
			$AppearanceSettings = new AppearanceSettings( $AppearanceSettings );
		}
		$this->AppearanceSettings = $AppearanceSettings;
	}

	/**
	 * @return AppearanceSettings|null
	 */
	public function get_appearance_settings(): ?AppearanceSettings {
		return $this->AppearanceSettings;
	}

	/**
	 * @param BiometricsSettings|array|null $BiometricsSettings
	 */
	public function set_biometrics_settings( $BiometricsSettings ): void {
		if ( is_array( $BiometricsSettings ) ) {
			$BiometricsSettings = new BiometricsSettings( $BiometricsSettings );
		}
		$this->BiometricsSettings = $BiometricsSettings;
	}

	/**
	 * @return BiometricsSettings|null
	 */
	public function get_biometrics_settings(): ?BiometricsSettings {
		return $this->BiometricsSettings;
	}

	/**
	 * @param CertificateSettings|array|null $CertificateSettings
	 */
	public function set_certificate_settings( $CertificateSettings ): void {
		if ( is_array( $CertificateSettings ) ) {
			$CertificateSettings = new CertificateSettings( $CertificateSettings );
		}
		$this->CertificateSettings = $CertificateSettings;
	}

	/**
	 * @return CertificateSettings|null
	 */
	public function get_certificate_settings(): ?CertificateSettings {
		return $this->CertificateSettings;
	}

	/**
	 * @param CommerceSettings|array|null $CommerceSettings
	 */
	public function set_commerce_settings( $CommerceSettings ): void {
		if ( is_array( $CommerceSettings ) ) {
			$CommerceSettings = new CommerceSettings( $CommerceSettings );
		}
		$this->CommerceSettings = $CommerceSettings;
	}

	/**
	 * @return CommerceSettings|null
	 */
	public function get_commerce_settings(): ?CommerceSettings {
		return $this->CommerceSettings;
	}

	/**
	 * @param InterestsSettings|array|null $InterestsSettings
	 */
	public function set_interests_settings( $InterestsSettings ): void {
		if ( is_array( $InterestsSettings ) ) {
			$InterestsSettings = new InterestsSettings( $InterestsSettings );
		}
		$this->InterestsSettings = $InterestsSettings;
	}

	/**
	 * @return InterestsSettings|null
	 */
	public function get_interests_settings(): ?InterestsSettings {
		return $this->InterestsSettings;
	}

	/**
	 * @param LoginSettings|array|null $LoginSettings
	 */
	public function set_login_settings( $LoginSettings ): void {
		if ( is_array( $LoginSettings ) ) {
			$LoginSettings = new LoginSettings( $LoginSettings );
		}
		$this->LoginSettings = $LoginSettings;
	}

	/**
	 * @return LoginSettings|null
	 */
	public function get_login_settings(): ?LoginSettings {
		return $this->LoginSettings;
	}

	/**
	 * @param MyBodySettings|array|null $MyBodySettings
	 */
	public function set_my_body_settings( $MyBodySettings ): void {
		if ( is_array( $MyBodySettings ) ) {
			$MyBodySettings = new MyBodySettings( $MyBodySettings );
		}
		$this->MyBodySettings = $MyBodySettings;
	}

	/**
	 * @return MyBodySettings|null
	 */
	public function get_my_body_settings(): ?MyBodySettings {
		return $this->MyBodySettings;
	}

	/**
	 * @param PlacesSettings|array|null $PlacesSettings
	 */
	public function set_places_settings( $PlacesSettings ): void {
		if ( is_array( $PlacesSettings ) ) {
			$PlacesSettings = new PlacesSettings( $PlacesSettings );
		}
		$this->PlacesSettings = $PlacesSettings;
	}

	/**
	 * @return PlacesSettings|null
	 */
	public function get_places_settings(): ?PlacesSettings {
		return $this->PlacesSettings;
	}

	/**
	 * @param ProfileSettings|array|null $ProfileSettings
	 */
	public function set_profile_settings( $ProfileSettings ): void {
		if ( is_array( $ProfileSettings ) ) {
			$ProfileSettings = new ProfileSettings( $ProfileSettings );
		}
		$this->ProfileSettings = $ProfileSettings;
	}

	/**
	 * @return ProfileSettings|null
	 */
	public function get_profile_settings(): ?ProfileSettings {
		return $this->ProfileSettings;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
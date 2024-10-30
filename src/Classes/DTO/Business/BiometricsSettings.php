<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Base, Enum\BiometricsPermission};

class BiometricsSettings extends Base {
	/** @var BiometricsPermission|null */
	private $BiometricsPermission;
	/** @var int */
	private $SettingId;
	/** @var bool */
	private $IsEnabled;

	public function __construct( ?array $BiometricsSettings = null ) {
		if ( is_array( $BiometricsSettings ) ) {
			$this->parse_array( $BiometricsSettings );
		}
	}

	/**
	 * @param BiometricsPermission|int|string|null $BiometricsPermission
	 */
	public function set_biometrics_permission( $BiometricsPermission ): void {
		if ( is_string( $BiometricsPermission ) ) {
			$BiometricsPermission = BiometricsPermission::$BiometricsPermission();
		}

		$this->BiometricsPermission = $BiometricsPermission;
	}

	/**
	 * @return BiometricsPermission
	 */
	public function get_biometrics_permission(): BiometricsPermission {
		return $this->BiometricsPermission;
	}

	/**
	 * @param int $SettingId
	 */
	public function set_setting_id( int $SettingId ): void {
		$this->SettingId = $SettingId;
	}

	/**
	 * @return int
	 */
	public function get_setting_id(): int {
		return $this->SettingId;
	}

	/**
	 * @param bool|int|string|null $IsEnabled
	 *
	 * @return self
	 */
	public function set_is_enabled( $IsEnabled ): self {
		if ( ! is_null( $IsEnabled ) ) {
			$IsEnabled = filter_var( $IsEnabled, FILTER_VALIDATE_BOOLEAN );
		}
		$this->IsEnabled = $IsEnabled;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_is_enabled(): bool {
		return $this->IsEnabled ?? false;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
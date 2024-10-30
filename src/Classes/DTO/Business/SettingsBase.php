<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Base;

class SettingsBase extends Base {
	/** @var int */
	private $SettingId;
	/** @var bool */
	private $IsEnabled;

	/**
	 * @param int $SettingId
	 *
	 * @return self
	 */
	public function set_setting_id( int $SettingId ): self {
		$this->SettingId = $SettingId;

		return $this;
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
		return $this->IsEnabled;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
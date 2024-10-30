<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Base, Enum\Theme};

class AppearanceSettings extends Base {
	/** @var Theme */
	private $Theme;
	/** @var string */
	private $MenuPlacement;
	/** @var int */
	private $SettingId;
	/** @var bool */
	private $IsEnabled;

	public function __construct( ?array $AppearanceSettings = null ) {
		if ( is_array( $AppearanceSettings ) ) {
			$this->parse_array( $AppearanceSettings );
		}
	}

	/**
	 * @param Theme|int|string|null $Theme
	 *
	 * @return self
	 */
	public function set_theme( $Theme ): self {
		if ( is_string( $Theme ) ) {
			$Theme = Theme::$Theme();
		}

		$this->Theme = $Theme;

		return $this;
	}

	/**
	 * @return Theme
	 */
	public function get_theme(): Theme {
		return $this->Theme ?? new Theme( Theme::Light );
	}

	/**
	 * @param string $MenuPlacement
	 *
	 * @return self
	 */
	public function set_menu_placement( string $MenuPlacement ): self {
		$this->MenuPlacement = $MenuPlacement;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_menu_placement(): string {
		return $this->MenuPlacement;
	}

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
		return $this->IsEnabled ?? false;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\Enum\PermissionType;

class PlacesSettings extends SettingsBase {
	/** @var PermissionType */
	private $Fleggard;
	/** @var PermissionType */
	private $Hm;
	/** @var PermissionType */
	private $Bilka;
	/** @var PermissionType */
	private $Rema1000;
	/** @var PermissionType */
	private $Zalando;
	/** @var PermissionType */
	private $Asos;
	/** @var PermissionType */
	private $Dba;

	public function __construct( ?array $PlacesSettings = null ) {
		if ( is_array( $PlacesSettings ) ) {
			$this->parse_array( $PlacesSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_fleggard( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Fleggard = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_fleggard(): PermissionType {
		return $this->Fleggard;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_hm( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Hm = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_hm(): PermissionType {
		return $this->Hm;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_bilka( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Bilka = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_bilka(): PermissionType {
		return $this->Bilka;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_rema_1000( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Rema1000 = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_rema_1000(): PermissionType {
		return $this->Rema1000;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_zalando( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Zalando = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_zalando(): PermissionType {
		return $this->Zalando;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_asos( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Asos = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_asos(): PermissionType {
		return $this->Asos;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_dba( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Dba = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_dba(): PermissionType {
		return $this->Dba;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
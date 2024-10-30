<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Enum\PermissionType};

class MyBodySettings extends SettingsBase {
	/** @var PermissionType */
	private $NeckSize;
	/** @var PermissionType */
	private $BreastSize;
	/** @var PermissionType */
	private $WaistSize;
	/** @var PermissionType */
	private $SeatWidth;
	/** @var PermissionType */
	private $SweaterLength;
	/** @var PermissionType */
	private $ShoulderWidth;
	/** @var PermissionType */
	private $ArmLengthTight;
	/** @var PermissionType */
	private $ArmLengthBent;
	/** @var PermissionType */
	private $Wrist;
	/** @var PermissionType */
	private $HipWidth;
	/** @var PermissionType */
	private $StrideLength;
	/** @var PermissionType */
	private $Jacketlength;
	/** @var PermissionType */
	private $ShoeSize;
	/** @var PermissionType */
	private $FootLength;

	public function __construct( ?array $MyBodySettings = null ) {
		if ( is_array( $MyBodySettings ) ) {
			$this->parse_array( $MyBodySettings );
		}
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_neck_size( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->NeckSize = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_neck_size(): PermissionType {
		return $this->NeckSize;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_breast_size( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->BreastSize = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_breast_size(): PermissionType {
		return $this->BreastSize;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_waist_size( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->WaistSize = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_waist_size(): PermissionType {
		return $this->WaistSize;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_seat_width( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->SeatWidth = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_seat_width(): PermissionType {
		return $this->SeatWidth;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_sweater_length( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->SweaterLength = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_sweater_length(): PermissionType {
		return $this->SweaterLength;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_shoulder_width( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->ShoulderWidth = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_shoulder_width(): PermissionType {
		return $this->ShoulderWidth;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_arm_length_tight( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->ArmLengthTight = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_arm_length_tight(): PermissionType {
		return $this->ArmLengthTight;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_arm_length_bent( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->ArmLengthBent = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_arm_length_bent(): PermissionType {
		return $this->ArmLengthBent;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_wrist( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Wrist = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_wrist(): PermissionType {
		return $this->Wrist;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_hip_width( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->HipWidth = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_hip_width(): PermissionType {
		return $this->HipWidth;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_stride_length( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->StrideLength = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_stride_length(): PermissionType {
		return $this->StrideLength;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_jacketlength( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->Jacketlength = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_jacketlength(): PermissionType {
		return $this->Jacketlength;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_shoe_size( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->ShoeSize = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_shoe_size(): PermissionType {
		return $this->ShoeSize;
	}

	/**
	 * @param PermissionType|int|string $PermissionType
	 *
	 * @return self
	 */
	public function set_foot_length( $PermissionType ): self {
		if ( is_string( $PermissionType ) ) {
			$PermissionType = PermissionType::$PermissionType();
		}
		$this->FootLength = new PermissionType( $PermissionType );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_foot_length(): PermissionType {
		return $this->FootLength;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
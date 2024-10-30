<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class MyBody extends Base {
	/** @var int|null */
	private $NeckSize;
	/** @var int|null */
	private $BreastSize;
	/** @var int|null */
	private $WaistSize;
	/** @var int|null */
	private $SeatWidth;
	/** @var int|null */
	private $SweaterLength;
	/** @var int|null */
	private $ShoulderWidth;
	/** @var int|null */
	private $ArmLengthTight;
	/** @var int|null */
	private $ArmLengthBent;
	/** @var int|null */
	private $Wrist;
	/** @var int|null */
	private $HipWidth;
	/** @var int|null */
	private $StrideLength;
	/** @var int|null */
	private $JacketLength;
	/** @var int|null */
	private $ShoeSize;
	/** @var int|null */
	private $FootLength;

	public function __construct( ?array $MyBody = null ) {
		if ( is_array( $MyBody ) ) {
			$this->parse_array( $MyBody );
		}
	}

	/**
	 * @param int|null $NeckSize
	 *
	 * @return MyBody
	 */
	public function set_neck_size( ?int $NeckSize ): self {
		$this->NeckSize = $NeckSize;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_neck_size(): ?int {
		return $this->NeckSize;
	}

	/**
	 * @param int|null $BreastSize
	 *
	 * @return MyBody
	 */
	public function set_breast_size( ?int $BreastSize ): self {
		$this->BreastSize = $BreastSize;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_breast_size(): ?int {
		return $this->BreastSize;
	}

	/**
	 * @param int|null $WaistSize
	 *
	 * @return MyBody
	 */
	public function set_waist_size( ?int $WaistSize ): self {
		$this->WaistSize = $WaistSize;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_waist_size(): ?int {
		return $this->WaistSize;
	}

	/**
	 * @param int|null $SeatWidth
	 *
	 * @return MyBody
	 */
	public function set_seat_width( ?int $SeatWidth ): self {
		$this->SeatWidth = $SeatWidth;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_seat_width(): ?int {
		return $this->SeatWidth;
	}

	/**
	 * @param int|null $SweaterLength
	 *
	 * @return MyBody
	 */
	public function set_sweater_length( ?int $SweaterLength ): self {
		$this->SweaterLength = $SweaterLength;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_sweater_length(): ?int {
		return $this->SweaterLength;
	}

	/**
	 * @param int|null $ShoulderWidth
	 *
	 * @return MyBody
	 */
	public function set_shoulder_width( ?int $ShoulderWidth ): self {
		$this->ShoulderWidth = $ShoulderWidth;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_shoulder_width(): ?int {
		return $this->ShoulderWidth;
	}

	/**
	 * @param int|null $ArmLengthTight
	 *
	 * @return MyBody
	 */
	public function set_arm_length_tight( ?int $ArmLengthTight ): self {
		$this->ArmLengthTight = $ArmLengthTight;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_arm_length_tight(): ?int {
		return $this->ArmLengthTight;
	}

	/**
	 * @param int|null $ArmLengthBent
	 *
	 * @return MyBody
	 */
	public function set_arm_length_bent( ?int $ArmLengthBent ): self {
		$this->ArmLengthBent = $ArmLengthBent;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_arm_length_bent(): ?int {
		return $this->ArmLengthBent;
	}

	/**
	 * @param int|null $Wrist
	 *
	 * @return MyBody
	 */
	public function set_wrist( ?int $Wrist ): self {
		$this->Wrist = $Wrist;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_wrist(): ?int {
		return $this->Wrist;
	}

	/**
	 * @param int|null $HipWidth
	 *
	 * @return MyBody
	 */
	public function set_hip_width( ?int $HipWidth ): self {
		$this->HipWidth = $HipWidth;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_hip_width(): ?int {
		return $this->HipWidth;
	}

	/**
	 * @param int|null $StrideLength
	 *
	 * @return MyBody
	 */
	public function set_stride_length( ?int $StrideLength ): self {
		$this->StrideLength = $StrideLength;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_stride_length(): ?int {
		return $this->StrideLength;
	}

	/**
	 * @param int|null $JacketLength
	 *
	 * @return MyBody
	 */
	public function set_jacket_length( ?int $JacketLength ): self {
		$this->JacketLength = $JacketLength;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_jacket_length(): ?int {
		return $this->JacketLength;
	}

	/**
	 * @param int|null $ShoeSize
	 *
	 * @return MyBody
	 */
	public function set_shoe_size( ?int $ShoeSize ): self {
		$this->ShoeSize = $ShoeSize;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_shoe_size(): ?int {
		return $this->ShoeSize;
	}

	/**
	 * @param int|null $FootLength
	 *
	 * @return MyBody
	 */
	public function set_foot_length( ?int $FootLength ): self {
		$this->FootLength = $FootLength;

		return $this;

	}

	/**
	 * @return int|null
	 */
	public function get_foot_length(): ?int {
		return $this->FootLength;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Enum\PermissionType};

class InterestsSettings extends SettingsBase {
	/** @var PermissionType */
	private $Housing;
	/** @var PermissionType */
	private $ClothingAndFashion;
	/** @var PermissionType */
	private $FoodAndDrinks;
	/** @var PermissionType */
	private $Transport;
	/** @var PermissionType */
	private $Technology;
	/** @var PermissionType */
	private $Sport;
	/** @var PermissionType */
	private $Creativity;
	/** @var PermissionType */
	private $SpareTime;
	/** @var PermissionType */
	private $Enternainment;
	/** @var PermissionType */
	private $Brands;

	public function __construct( ?array $InterestsSettings = null ) {
		if ( is_array( $InterestsSettings ) ) {
			$this->parse_array( $InterestsSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $Housing
	 *
	 * @return self
	 */
	public function set_housing( $Housing ): self {
		if ( is_string( $Housing ) ) {
			$Housing = PermissionType::$Housing();
		}
		$this->Housing = new PermissionType( $Housing );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_housing(): PermissionType {
		return $this->Housing;
	}

	/**
	 * @param PermissionType|int|string $ClothingAndFashion
	 *
	 * @return self
	 */
	public function set_clothing_and_fashion( $ClothingAndFashion ): self {
		if ( is_string( $ClothingAndFashion ) ) {
			$ClothingAndFashion = PermissionType::$ClothingAndFashion();
		}
		$this->ClothingAndFashion = new PermissionType( $ClothingAndFashion );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_clothing_and_fashion(): PermissionType {
		return $this->ClothingAndFashion;
	}

	/**
	 * @param PermissionType|int|string $FoodAndDrinks
	 *
	 * @return self
	 */
	public function set_food_and_drinks( $FoodAndDrinks ): self {
		if ( is_string( $FoodAndDrinks ) ) {
			$FoodAndDrinks = PermissionType::$FoodAndDrinks();
		}
		$this->FoodAndDrinks = new PermissionType( $FoodAndDrinks );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_food_and_drinks(): PermissionType {
		return $this->FoodAndDrinks;
	}

	/**
	 * @param PermissionType|int|string $Transport
	 *
	 * @return self
	 */
	public function set_transport( $Transport ): self {
		if ( is_string( $Transport ) ) {
			$Transport = PermissionType::$Transport();
		}
		$this->Transport = new PermissionType( $Transport );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_transport(): PermissionType {
		return $this->Transport;
	}

	/**
	 * @param PermissionType|int|string $Technology
	 *
	 * @return self
	 */
	public function set_technology( $Technology ): self {
		if ( is_string( $Technology ) ) {
			$Technology = PermissionType::$Technology();
		}
		$this->Technology = new PermissionType( $Technology );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_technology(): PermissionType {
		return $this->Technology;
	}

	/**
	 * @param PermissionType|int|string $Sport
	 *
	 * @return self
	 */
	public function set_sport( $Sport ): self {
		if ( is_string( $Sport ) ) {
			$Sport = PermissionType::$Sport();
		}
		$this->Sport = new PermissionType( $Sport );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_sport(): PermissionType {
		return $this->Sport;
	}

	/**
	 * @param PermissionType|int|string $Creativity
	 *
	 * @return self
	 */
	public function set_creativity( $Creativity ): self {
		if ( is_string( $Creativity ) ) {
			$Creativity = PermissionType::$Creativity();
		}
		$this->Creativity = new PermissionType( $Creativity );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_creativity(): PermissionType {
		return $this->Creativity;
	}

	/**
	 * @param PermissionType|int|string $SpareTime
	 *
	 * @return self
	 */
	public function set_spare_time( $SpareTime ): self {
		if ( is_string( $SpareTime ) ) {
			$SpareTime = PermissionType::$SpareTime();
		}
		$this->SpareTime = new PermissionType( $SpareTime );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_spare_time(): PermissionType {
		return $this->SpareTime;
	}

	/**
	 * @param PermissionType|int|string $Enternainment
	 *
	 * @return self
	 */
	public function set_enternainment( $Enternainment ): self {
		if ( is_string( $Enternainment ) ) {
			$Enternainment = PermissionType::$Enternainment();
		}
		$this->Enternainment = new PermissionType( $Enternainment );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_enternainment(): PermissionType {
		return $this->Enternainment;
	}

	/**
	 * @param PermissionType|int|string $Brands
	 *
	 * @return self
	 */
	public function set_brands( $Brands ): self {
		if ( is_string( $Brands ) ) {
			$Brands = PermissionType::$Brands();
		}
		$this->Brands = new PermissionType( $Brands );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_brands(): PermissionType {
		return $this->Brands;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}

}
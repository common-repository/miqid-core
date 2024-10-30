<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use MIQID\Plugin\Core\Classes\DTO\Enum\AddressType;

class Address extends Base {
	/** @var string|null */
	private $Id;
	/** @var AddressType|null */
	private $Type;
	/** @var string|null */
	private $Name;
	/** @var string|null */
	private $Order;
	/** @var string|null */
	private $Street;
	/** @var int|null */
	private $StreetNumber;
	/** @var string|null */
	private $SideDoorFloor;
	/** @var string|null */
	private $Street2;
	/** @var int|null */
	private $StreetNumber2;
	/** @var string|null */
	private $SideDoorFloor2;
	/** @var string|null */
	private $Country;
	/** @var string|null */
	private $PostalCode;
	/** @var string|null */
	private $City;

	public function __construct( ?array $Address = null ) {
		$this->Type = new AddressType( AddressType::PrimaryAddress );
		if ( is_array( $Address ) ) {
			$this->parse_array( $Address );
		}
	}

	/**
	 * @param string|null $Id
	 *
	 * @return self
	 */
	public function set_id( ?string $Id ): self {
		$this->Id = $Id;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_id(): ?string {
		return $this->Id;
	}

	/**
	 * @param AddressType|int|string|null $Type
	 *
	 * @return self
	 */
	public function set_type( $Type ): self {
		if ( is_string( $Type ) ) {
			$Type = AddressType::$Type();
		}
		$this->Type = new AddressType( $Type );

		return $this;
	}

	/**
	 * @return AddressType|null
	 */
	public function get_type(): ?AddressType {
		return $this->Type;
	}

	/**
	 * @param string|null $Name
	 *
	 * @return self
	 */
	public function set_name( ?string $Name ): self {
		$this->Name = $Name;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_name(): ?string {
		return $this->Name;
	}

	/**
	 * @param string|null $Order
	 *
	 * @return self
	 */
	public function set_order( ?string $Order ): self {
		$this->Order = $Order;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_order(): ?string {
		return $this->Order;
	}

	/**
	 * @param string|null $Street
	 *
	 * @return self
	 */
	public function set_street( ?string $Street ): self {
		$this->Street = $Street;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_street(): ?string {
		return $this->Street;
	}

	/**
	 * @param int|null $StreetNumber
	 *
	 * @return self
	 */
	public function set_street_number( ?int $StreetNumber ): self {
		$this->StreetNumber = $StreetNumber;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function get_street_number(): ?int {
		return $this->StreetNumber;
	}

	/**
	 * @param string|null $SideDoorFloor
	 *
	 * @return self
	 */
	public function set_side_door_floor( ?string $SideDoorFloor ): self {
		$this->SideDoorFloor = $SideDoorFloor;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_side_door_floor(): ?string {
		return $this->SideDoorFloor;
	}

	/**
	 * @param string|null $Street2
	 *
	 * @return self
	 */
	public function set_street_2( ?string $Street2 ): self {
		$this->Street2 = $Street2;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_street_2(): ?string {
		return $this->Street2;
	}

	/**
	 * @param int|null $StreetNumber2
	 *
	 * @return self
	 */
	public function set_street_number_2( ?int $StreetNumber2 ): self {
		$this->StreetNumber2 = $StreetNumber2;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function get_street_number_2(): ?int {
		return $this->StreetNumber2;
	}

	/**
	 * @param string|null $SideDoorFloor2
	 *
	 * @return self
	 */
	public function set_side_door_floor_2( ?string $SideDoorFloor2 ): self {
		$this->SideDoorFloor2 = $SideDoorFloor2;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_side_door_floor_2(): ?string {
		return $this->SideDoorFloor2;
	}

	/**
	 * @param string|null $Country
	 *
	 * @return self
	 */
	public function set_country( ?string $Country ): self {
		$this->Country = $Country;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_country(): ?string {
		return $this->Country;
	}

	/**
	 * @param string|null $PostalCode
	 *
	 * @return self
	 */
	public function set_postal_code( ?string $PostalCode ): self {
		$this->PostalCode = $PostalCode;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_postal_code(): ?string {
		return $this->PostalCode;
	}

	/**
	 * @param string|null $City
	 *
	 * @return self
	 */
	public function set_city( ?string $City ): self {
		$this->City = $City;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_city(): ?string {
		return $this->City;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class CompanyType extends Base {
	const Partner      = 1;
	const Website      = 2;
	const PhysicalShop = 3;
	const MIQID        = 99;

	/** @var int */
	private $types;
	/** @var CompanyRole */
	private $roles;

	public function __construct( $types = null, $roles = null ) {
		$this->set_types( $types );
		$this->set_roles( $roles );
	}

	/**
	 * @param array|int|null $types
	 */
	public function set_types( $types ): void {
		if ( is_array( $types ) ) {
			$this->parse_array( $types );
		} else {
			if ( isset( $types ) && ! in_array( $types, [
					self::Partner,
					self::Website,
					self::PhysicalShop,
					self::MIQID,
				] ) ) {
				$types = null;
			}

			$this->types = $types ?? $this->types;
		}
	}

	/**
	 * @param array|CompanyRole|null $roles
	 */
	public function set_roles( $roles ): void {
		if ( is_array( $roles ) ) {
			$roles = new CompanyRole( $roles );
		}
		$this->roles = $roles ?? $this->roles;
	}

	/**
	 * @return int
	 */
	public function get_types(): int {
		return $this->types ?? self::Partner;
	}

	/**
	 * @return CompanyRole
	 */
	public function get_roles(): CompanyRole {
		return $this->roles ?? new CompanyRole();
	}

	public function jsonSerialize(): array {
		$arr          = get_object_vars( $this );
		$arr['roles'] = $this->get_roles();

		return $arr;
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class CompanyCountryDepartment extends CompanyCountry {
	/** @var string */
	private $name;
	/** @var string */
	private $companyCountryId;

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		$arr = array_merge(
			get_object_vars( $this ),
			parent::jsonSerialize()
		);

		unset( $arr['companyCountryName'] );
		unset( $arr['country'] );
		unset( $arr['companyId'] );

		return $arr;
	}
}
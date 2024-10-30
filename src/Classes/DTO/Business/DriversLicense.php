<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

class DriversLicense extends \MIQID\Plugin\Core\Classes\DTO\DriversLicense {

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
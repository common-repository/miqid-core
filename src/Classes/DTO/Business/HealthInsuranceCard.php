<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

class HealthInsuranceCard extends \MIQID\Plugin\Core\Classes\DTO\HealthInsuranceCard {

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
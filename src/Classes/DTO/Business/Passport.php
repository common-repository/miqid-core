<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

class Passport extends \MIQID\Plugin\Core\Classes\DTO\Passport {
	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
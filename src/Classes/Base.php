<?php

namespace MIQID\Plugin\Core\Classes;

use JsonSerializable;
use MIQID\Plugin\Core\Util;

abstract class Base implements JsonSerializable {
	/**
	 * @param array $array The to parse to object, will be cleared after.
	 *
	 * @return self
	 */
	protected function parse_array( array &$array ): self {
		foreach ( $array as $key => $value ) {
			$function = sprintf( 'set_%s', Util::snake_case( $key ) );
			if ( method_exists( $this, $function ) ) {
				$this->$function( $value );
			} else if ( property_exists( $this, $key ) ) {
				$this->{$key} = $value;
			}
		}
		$array = null;

		return $this;
	}
}
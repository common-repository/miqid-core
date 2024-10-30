<?php

namespace MIQID\Core;

abstract class Base {
	abstract public static function Instance();

	static function ToObject( array $array, $object ) {

		foreach ( $array as $key => $value ) {
			if ( ! property_exists( $object, $key ) ) {
				continue;
			}

			$object->{$key} = $value;
		}

		return $object;
	}
}
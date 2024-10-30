<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class ProfilePicture extends IFormFile {

	public function __construct( $contentType = null, $contentDisposition = null, $headers = null, $length = null, $name = null, $fileName = null ) {
		parent::__construct( $contentType, $contentDisposition, $headers, $length, $name, $fileName );
	}

	public function jsonSerialize(): array {
		return array_merge(
			get_object_vars( $this ),
			parent::jsonSerialize()
		);
	}
}
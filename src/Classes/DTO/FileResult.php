<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class FileResult extends Base {
	/** @var string|null */
	private $ContentType;
	/** @var string|null */
	private $FileDownloadName;

	public function __construct( ?array $FileResult = null ) {
		if ( is_array( $FileResult ) ) {
			$this->parse_array( $FileResult );
		}
	}

	/**
	 * @param string|null $ContentType
	 */
	public function set_content_type( ?string $ContentType ): void {
		$this->ContentType = $ContentType;
	}

	/**
	 * @return string|null
	 */
	public function get_content_type(): ?string {
		return $this->ContentType;
	}

	/**
	 * @param string|null $FileDownloadName
	 */
	public function set_file_download_name( ?string $FileDownloadName ): void {
		$this->FileDownloadName = $FileDownloadName;
	}

	/**
	 * @return string|null
	 */
	public function get_file_download_name(): ?string {
		return $this->FileDownloadName;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
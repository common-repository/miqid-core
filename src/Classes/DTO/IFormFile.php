<?php

namespace MIQID\Plugin\Core\Classes\DTO;

abstract class IFormFile extends Base {
	/** @var string|null */
	private $contentType;
	/** @var string|null */
	private $contentDisposition;
	/** @var array|null */
	private $headers;
	/** @var int|null */
	private $length;
	/** @var string|null */
	private $name;
	/** @var string|null */
	private $fileName;

	public function __construct(
		$contentType = null,
		$contentDisposition = null,
		$headers = null,
		$length = null,
		$name = null,
		$fileName = null
	) {
		$this->set_content_type( $contentType );
		$this->set_content_disposition( $contentDisposition );
		$this->set_headers( $headers );
		$this->set_length( $length );
		$this->set_name( $name );
		$this->set_file_name( $fileName );
	}

	/**
	 * @param array|string|null $contentType
	 */
	public function set_content_type( $contentType ): void {
		if ( is_array( $contentType ) ) {
			$this->parse_array( $contentType );
		} else {
			$this->contentType = $contentType ?? $this->contentType;
		}
	}

	/**
	 * @param string|null $contentDisposition
	 */
	public function set_content_disposition( ?string $contentDisposition ): void {
		$this->contentDisposition = $contentDisposition ?? $this->contentDisposition;
	}

	/**
	 * @param array|null $headers
	 */
	public function set_headers( ?array $headers ): void {
		$this->headers = $headers ?? $this->headers;
	}

	/**
	 * @param int|null $length
	 */
	public function set_length( ?int $length ): void {
		$this->length = $length ?? $this->length;
	}

	/**
	 * @param string|null $name
	 */
	public function set_name( ?string $name ): void {
		$this->name = $name ?? $this->name;
	}

	/**
	 * @param string|null $fileName
	 */
	public function set_file_name( ?string $fileName ): void {
		$this->fileName = $fileName ?? $this->fileName;
	}

	/**
	 * @return string
	 */
	public function get_content_type(): string {
		return $this->contentType ?? '';
	}

	/**
	 * @return string
	 */
	public function get_content_disposition(): string {
		return $this->contentDisposition ?? '';
	}

	/**
	 * @return array|null
	 */
	public function get_headers(): ?array {
		return $this->headers ?? [];
	}

	/**
	 * @return int
	 */
	public function get_length(): int {
		return $this->length ?? 0;
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name ?? '';
	}

	/**
	 * @return string
	 */
	public function get_file_name(): string {
		return $this->fileName ?? '';
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use MIQID\Plugin\Core\Classes\DTO\Enum\FileContentResultType;

class FileContentResult extends FileResult {
	/** @var string|null */
	private $FileContents;
	/** @var FileContentResultType */
	private $FileContentResultType;

	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct( ?array $FileContentResult = [] ) {
		if ( is_array( $FileContentResult ) ) {
			$this->parse_array( $FileContentResult );
		}
	}

	/**
	 * @param string|null $FileContents
	 *
	 * @return self
	 */
	public function set_file_contents( ?string $FileContents ): self {
		$this->FileContents = $FileContents;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_file_contents(): ?string {
		return $this->FileContents;
	}

	/**
	 * @param FileContentResultType|int|string $FileContentResultType
	 *
	 * @return self
	 */
	public function set_file_content_result_type( $FileContentResultType ): self {
		if ( is_string( $FileContentResultType ) ) {
			$FileContentResultType = FileContentResultType::$FileContentResultType();
		}
		$this->FileContentResultType = new FileContentResultType( $FileContentResultType );

		return $this;
	}

	/**
	 * @return FileContentResultType
	 */
	public function get_file_content_result_type(): FileContentResultType {
		return $this->FileContentResultType ?? new FileContentResultType( FileContentResultType::NotSet );
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
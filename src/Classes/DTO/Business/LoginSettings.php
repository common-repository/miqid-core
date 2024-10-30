<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Enum\PermissionType};

class LoginSettings extends SettingsBase {
	/** @var PermissionType */
	private $Facebook;
	/** @var PermissionType */
	private $Instagram;
	/** @var PermissionType */
	private $Twitter;
	/** @var PermissionType */
	private $LinkedIn;

	public function __construct( ?array $LoginSettings = null ) {
		if ( is_array( $LoginSettings ) ) {
			$this->parse_array( $LoginSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $Facebook
	 *
	 * @return self
	 */
	public function set_facebook( $Facebook ): self {
		if ( is_string( $Facebook ) ) {
			$Facebook = PermissionType::$Facebook();
		}
		$this->Facebook = new PermissionType( $Facebook );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_facebook(): PermissionType {
		return $this->Facebook;
	}

	/**
	 * @param PermissionType|int|string $Instagram
	 *
	 * @return self
	 */
	public function set_instagram( $Instagram ): self {
		if ( is_string( $Instagram ) ) {
			$Instagram = PermissionType::$Instagram();
		}
		$this->Instagram = new PermissionType( $Instagram );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_instagram(): PermissionType {
		return $this->Instagram;
	}

	/**
	 * @param PermissionType|int|string $Twitter
	 *
	 * @return self
	 */
	public function set_twitter( $Twitter ): self {
		if ( is_string( $Twitter ) ) {
			$Twitter = PermissionType::$Twitter();
		}
		$this->Twitter = new PermissionType( $Twitter );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_twitter(): PermissionType {
		return $this->Twitter;
	}

	/**
	 * @param PermissionType|int|string $LinkedIn
	 *
	 * @return self
	 */
	public function set_linked_in( $LinkedIn ): self {
		if ( is_string( $LinkedIn ) ) {
			$LinkedIn = PermissionType::$LinkedIn();
		}
		$this->LinkedIn = new PermissionType( $LinkedIn );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_linked_in(): PermissionType {
		return $this->LinkedIn;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}
}
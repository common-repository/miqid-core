<?php

namespace MIQID\Plugin\Core\Classes;

class Settings extends Base {
	/** @var Endpoint */
	private $Endpoint_Private;
	/** @var Endpoint */
	private $Endpoint_Business;

	/** @var string|null */
	private $JWT;
	/** @var string|null */
	private $date_format;
	/** @var string|null */
	private $time_format;
	/** @var string|null */
	private $date_time_format;
	/** @var bool */
	private $user_switching_enabled;
	/** @var string|null */
	private $cvr;

	public function __construct( ?array $Feature = null ) {
		if ( is_array( $Feature ) ) {
			$this->parse_array( $Feature );
		}
	}

	/**
	 * @param Endpoint|array|null $Endpoint_Private
	 */
	public function set_endpoint_private( $Endpoint_Private ): void {
		if ( is_array( $Endpoint_Private ) ) {
			$Endpoint_Private = new Endpoint( $Endpoint_Private );
		}
		$this->Endpoint_Private = $Endpoint_Private;
	}

	/**
	 * @return Endpoint
	 */
	public function get_endpoint_private(): Endpoint {
		return $this->Endpoint_Private ?? ( new Endpoint() )
				->set_host( 'https://api.private.miqid.com/api' );
	}

	/**
	 * @param Endpoint|array|null $Endpoint_Business
	 */
	public function set_endpoint_business( $Endpoint_Business ): void {
		if ( is_array( $Endpoint_Business ) ) {
			$Endpoint_Business = new Endpoint( $Endpoint_Business );
		}
		$this->Endpoint_Business = $Endpoint_Business;
	}

	/**
	 * @return Endpoint
	 */
	public function get_endpoint_business(): Endpoint {
		return $this->Endpoint_Business ?? ( new Endpoint() )
				->set_host( 'https://api.business.miqid.com/api' )
				->set_version( 'v0.2' );
	}

	/**
	 * @param string|null $JWT
	 */
	public function set_JWT( ?string $JWT ): void {
		$this->JWT = $JWT;
	}

	/**
	 * @return string|null
	 */
	public function get_JWT(): ?string {
		return $this->JWT;
	}

	/**
	 * @param string|null $date_format
	 */
	public function set_date_format( ?string $date_format ): void {
		$this->date_format = $date_format;
	}

	/**
	 * @param  bool  $allow_fallback
	 *
	 * @return string|null
	 */
	public function get_date_format( bool $allow_fallback = true ): ?string {
		if ( $allow_fallback && empty( $this->date_format ) ) {
			return get_option( 'date_format' );
		}

		return $this->date_format;
	}

	/**
	 * @param string|null $time_format
	 */
	public function set_time_format( ?string $time_format ): void {
		$this->time_format = $time_format;
	}

	/**
	 * @param  bool  $allow_fallback
	 *
	 * @return string|null
	 */
	public function get_time_format( bool $allow_fallback = true ): ?string {
		if ( $allow_fallback && empty( $this->time_format ) ) {
			return get_option( 'time_format' );
		}

		return $this->time_format;
	}

	/**
	 * @param string|null $date_time_format
	 */
	public function set_date_time_format( ?string $date_time_format ): void {
		$this->date_time_format = $date_time_format;
	}

	/**
	 * @param  bool  $allow_fallback
	 *
	 * @return string|null
	 */
	public function get_date_time_format( bool $allow_fallback = true ): ?string {
		if ( $allow_fallback && empty( $this->date_time_format ) ) {
			return sprintf( '%s %s', $this->get_time_format(), $this->get_date_format() );
		}

		return $this->date_time_format;
	}

	/**
	 * @param bool|int|string|null $user_switching_enabled
	 */
	public function set_user_switching_enabled( $user_switching_enabled ): void {
		if ( ! is_null( $user_switching_enabled ) ) {
			$user_switching_enabled = filter_var( $user_switching_enabled, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		}
		$this->user_switching_enabled = $user_switching_enabled;
	}

	/**
	 * @return bool
	 */
	public function is_user_switching_enabled(): bool {
		return $this->user_switching_enabled ?? false;
	}

	/**
	 * @param string|null $cvr
	 *
	 * @return Settings
	 */
	public function set_cvr( ?string $cvr ): self {
		$this->cvr = $cvr;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function get_cvr(): ?string {
		return $this->cvr;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
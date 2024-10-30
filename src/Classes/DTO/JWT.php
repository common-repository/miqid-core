<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class JWT extends Base {
	/** @var string */
	private $jwt;
	private $JWT_Header;
	/** @var JWT_Payload|null */
	private $JWT_Payload;

	public function __construct( $jwt = null ) {
		if ( is_array( $jwt ) ) {
			$this->parse_array( $jwt );
		} else if ( is_string( $jwt ) ) {
			$this->set_jwt( $jwt );
		}
	}

	/**
	 * @param string|null $jwt
	 *
	 * @return JWT
	 */
	public function set_jwt( ?string $jwt ): JWT {
		$this->jwt = $jwt;

		return $this->decode();
	}

	/**
	 * @return string
	 */
	public function get_jwt(): string {
		return $this->jwt ?? '';
	}

	/**
	 * @param mixed $JWT_Header
	 */
	public function set_jwt_header( $JWT_Header ): void {
		$this->JWT_Header = $JWT_Header;
	}

	/**
	 * @return mixed
	 */
	public function get_jwt_header() {
		return $this->JWT_Header;
	}

	/**
	 * @param JWT_Payload|array|null $JWT_Payload
	 */
	public function set_jwt_payload( $JWT_Payload ): void {
		if ( is_array( $JWT_Payload ) ) {
			$JWT_Payload = new JWT_Payload( $JWT_Payload );
		}
		$this->JWT_Payload = $JWT_Payload;
	}

	/**
	 * @return JWT_Payload|null
	 */
	public function get_jwt_payload(): ?JWT_Payload {
		return $this->JWT_Payload ?? new JWT_Payload();
	}

	function decode(): self {
		$arr         = explode( '.', $this->get_jwt() );
		$JWT_Header  = base64_decode( array_shift( $arr ) );
		$JWT_Payload = base64_decode( array_shift( $arr ) );

		if ( is_string( $JWT_Header ) ) {
			$JWT_Header = json_decode( $JWT_Header, true );
		}

		if ( is_string( $JWT_Payload ) ) {
			$JWT_Payload = json_decode( $JWT_Payload, true );
		}

		if ( is_array( $JWT_Payload ) ) {
			$JWT_Payload = new JWT_Payload( $JWT_Payload );
		}

		$this->JWT_Header  = $JWT_Header;
		$this->JWT_Payload = $JWT_Payload;

		return $this;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}

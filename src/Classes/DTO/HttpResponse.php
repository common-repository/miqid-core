<?php

namespace MIQID\Plugin\Core\Classes\DTO;

use Requests_Utility_CaseInsensitiveDictionary;
use WP_Error;

class HttpResponse extends Base {
	/** @var int|string */
	private $response_code;
	/** @var string|null */
	private $response_message;
	/** @var array|Requests_Utility_CaseInsensitiveDictionary */
	private $headers;
	/** @var string|null */
	private $body;
	/** @var string|null */
	private $url;
	/** @var array|null */
	private $args;

	/**
	 * HttpResponse constructor.
	 *
	 * @param array|WP_Error $response
	 * @param $url
	 * @param $args
	 */
	public function __construct( $response, $url, $args ) {
		if ( is_wp_error( $response ) ) {
			$this->set_response_message( $response->get_error_message() );
		} else {
			$this->set_headers( wp_remote_retrieve_headers( $response ) );
			$this->set_response_message( wp_remote_retrieve_response_message( $response ) );
			$this->set_response_code( wp_remote_retrieve_response_code( $response ) );
			$this->set_body( wp_remote_retrieve_body( $response ) );
		}
		$this->set_url( $url );
		$this->set_args( $args );
	}

	/**
	 * @param int|string $response_code
	 */
	public function set_response_code( $response_code ): void {
		if ( is_string( $response_code ) ) {
			$response_code = filter_var( $response_code, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE );
		}
		$this->response_code = $response_code;
	}

	/** @return int */
	public function get_response_code(): int {
		return $this->response_code ?? 0;
	}

	/**
	 * @param string|null $response_message
	 */
	public function set_response_message( ?string $response_message ): void {
		$this->response_message = $response_message;
	}

	/** @return string */
	public function get_response_message(): string {
		return $this->response_message ?? '';
	}

	/**
	 * @param array|Requests_Utility_CaseInsensitiveDictionary $headers
	 */
	public function set_headers( $headers ): void {
		$this->headers = $headers;
	}

	/**
	 * @return array|Requests_Utility_CaseInsensitiveDictionary
	 */
	public function get_headers() {
		return $this->headers;
	}

	/**
	 * @param string|null $body
	 */
	public function set_body( ?string $body ): void {
		$this->body = $body;
	}

	/**
	 * @param  bool  $json_decode
	 *
	 * @return array|string
	 */
	public function get_body( bool $json_decode = true ) {
		if ( $json_decode ) {
			return json_decode( $this->body, true );
		}

		return $this->body ?? '';
	}

	/**
	 * @param string|null $url
	 */
	public function set_url( ?string $url ): void {
		$this->url = $url;
	}

	/**
	 * @return string|null
	 */
	public function get_url(): ?string {
		return $this->url;
	}

	/**
	 * @param array|null $args
	 */
	public function set_args( ?array $args ): void {
		$this->args = $args;
	}

	/**
	 * @return array|null
	 */
	public function get_args(): ?array {
		return $this->args;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}
}
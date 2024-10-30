<?php

namespace MIQID\Plugin\Core\Classes\DTO;

class JWT_Payload extends Base {
	/** @var string|null */
	private $AccountId;
	/** @var string|null */
	private $ProfileId;
	/** @var string|null */
	private $Role;
	/** @var string|null */
	private $RefreshToken;
	/** @var string|null */
	private $nbf;
	/** @var string|null */
	private $exp;
	/** @var string|null */
	private $iat;

	public function __construct( ?array $JWT_Payload = null ) {
		if ( is_array( $JWT_Payload ) ) {
			$this->parse_array( $JWT_Payload );
		}
	}

	/**
	 * @param string|null $AccountId
	 */
	public function set_account_id( ?string $AccountId ): void {
		$this->AccountId = $AccountId;
	}

	/**
	 * @return string|null
	 */
	public function get_account_id(): ?string {
		return $this->AccountId;
	}

	/**
	 * @param string|null $ProfileId
	 */
	public function set_profile_id( ?string $ProfileId ): void {
		$this->ProfileId = $ProfileId;
	}

	/**
	 * @return string|null
	 */
	public function get_profile_id(): ?string {
		return $this->ProfileId;
	}

	/**
	 * @param string|null $Role
	 */
	public function set_role( ?string $Role ): void {
		$this->Role = $Role;
	}

	/**
	 * @return string|null
	 */
	public function get_role(): ?string {
		return $this->Role;
	}

	/**
	 * @param string|null $RefreshToken
	 */
	public function set_refresh_token( ?string $RefreshToken ): void {
		$this->RefreshToken = $RefreshToken;
	}

	/**
	 * @return string|null
	 */
	public function get_refresh_token(): ?string {
		return $this->RefreshToken;
	}

	/**
	 * @param string|null $nbf
	 */
	public function set_nbf( ?string $nbf ): void {
		$this->nbf = $nbf;
	}

	/**
	 * @return string|null
	 */
	public function get_nbf(): ?string {
		return $this->nbf;
	}

	/**
	 * @param string|null $exp
	 */
	public function set_exp( ?string $exp ): void {
		$this->exp = $exp;
	}

	/**
	 * @return string|null
	 */
	public function get_exp(): ?string {
		return $this->exp;
	}

	/**
	 * @param string|null $iat
	 */
	public function set_iat( ?string $iat ): void {
		$this->iat = $iat;
	}

	/**
	 * @return string|null
	 */
	public function get_iat(): ?string {
		return $this->iat;
	}

	public function jsonSerialize(): array {
		return get_object_vars( $this );
	}

}
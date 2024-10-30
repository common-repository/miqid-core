<?php

namespace MIQID\Plugin\Core\Classes\DTO\Business;

use MIQID\Plugin\Core\Classes\DTO\{Enum\PermissionType};

class CommerceSettings extends SettingsBase {
	/** @var PermissionType */
	private $PackageRecievers;
	/** @var PermissionType */
	private $MailAddresses;
	/** @var PermissionType */
	private $PriorityDelivery;
	/** @var PermissionType */
	private $PaymentCard;

	public function __construct( ?array $CommerceSettings = null ) {
		if ( is_array( $CommerceSettings ) ) {
			$this->parse_array( $CommerceSettings );
		}
	}

	/**
	 * @param PermissionType|int|string $PackageRecievers
	 *
	 * @return self
	 */
	public function set_package_recievers( $PackageRecievers ): self {
		if ( is_string( $PackageRecievers ) ) {
			$PackageRecievers = PermissionType::$PackageRecievers();
		}
		$this->PackageRecievers = new PermissionType( $PackageRecievers );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_package_recievers(): PermissionType {
		return $this->PackageRecievers;
	}

	/**
	 * @param PermissionType|int|string $MailAddresses
	 *
	 * @return self
	 */
	public function set_mail_addresses( $MailAddresses ): self {
		if ( is_string( $MailAddresses ) ) {
			$MailAddresses = PermissionType::$MailAddresses();
		}
		$this->MailAddresses = new PermissionType( $MailAddresses );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_mail_addresses(): PermissionType {
		return $this->MailAddresses;
	}

	/**
	 * @param PermissionType|int|string $PriorityDelivery
	 *
	 * @return self
	 */
	public function set_priority_delivery( $PriorityDelivery ): self {
		if ( is_string( $PriorityDelivery ) ) {
			$PriorityDelivery = PermissionType::$PriorityDelivery();
		}
		$this->PriorityDelivery = new PermissionType( $PriorityDelivery );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_priority_delivery(): PermissionType {
		return $this->PriorityDelivery;
	}

	/**
	 * @param PermissionType|int|string $PaymentCard
	 *
	 * @return self
	 */
	public function set_payment_card( $PaymentCard ): self {
		if ( is_string( $PaymentCard ) ) {
			$PaymentCard = PermissionType::$PaymentCard();
		}

		$this->PaymentCard = new PermissionType( $PaymentCard );

		return $this;
	}

	/**
	 * @return PermissionType
	 */
	public function get_payment_card(): PermissionType {
		return $this->PaymentCard;
	}

	public function jsonSerialize(): array {
		return array_merge(
			parent::jsonSerialize(),
			get_object_vars( $this )
		);
	}

}
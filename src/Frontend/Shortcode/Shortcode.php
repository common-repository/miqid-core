<?php

namespace MIQID\Plugin\Core\Frontend\Shortcode;

use DateTime;
use MIQID\Plugin\Core\Classes\DTO\Enum\FileContentResultType;
use MIQID\Plugin\Core\Classes\DTO\FileContentResult;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\Certificate as shortcodeBusinessCertificate;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\Kyc as shortcodeBusinessKyc;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\MyBody as shortcodeBusinessMyBody;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\Profile as shortcodeBusinessProfile;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\UserAddress as shortcodeBusinessAddress;
use MIQID\Plugin\Core\Classes\DTO\Address as dtoPrivateAddress;
use MIQID\Plugin\Core\Classes\DTO\Business\DriversLicense as dtoBusinessDriversLicense;
use MIQID\Plugin\Core\Classes\DTO\Business\HealthInsuranceCard as dtoBusinessHealthInsuranceCard;
use MIQID\Plugin\Core\Classes\DTO\Business\MyBody as dtoBusinessMyBody;
use MIQID\Plugin\Core\Classes\DTO\Business\Passport as dtoBusinessPassport;
use MIQID\Plugin\Core\Classes\DTO\Business\Profile as dtoBusinessProfile;
use MIQID\Plugin\Core\Classes\DTO\Business\Showcase as dtoBusinessShowcase;
use MIQID\Plugin\Core\Classes\DTO\Business\UserAddress as dtoBusinessAddress;
use MIQID\Plugin\Core\Classes\DTO\DriversLicense as dtoPrivateDriversLicense;
use MIQID\Plugin\Core\Classes\DTO\Enum\PermissionType as enumPermission;
use MIQID\Plugin\Core\Classes\DTO\HttpResponse;
use MIQID\Plugin\Core\Classes\DTO\MyBody as dtoPrivateMyBody;
use MIQID\Plugin\Core\Classes\DTO\Passport as dtoPrivatePassport;
use MIQID\Plugin\Core\Classes\DTO\Profile as dtoPrivateProfile;
use MIQID\Plugin\Core\Classes\API\Business\Showcase;
use MIQID\Plugin\Core\Frontend\Shortcode\Business\UserAuthentication;
use MIQID\Plugin\Core\Util;
use MyCLabs\Enum\Enum;

class Shortcode {
	private static $instance;
	private static $Showcase = [];

	static function Instance(): Shortcode {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		Login::Instance();
		MIQID::Instance();

		Address::Instance();
		Certificate::Instance();
		MyBody::Instance();
		Profile::Instance();

		shortcodeBusinessKyc::Instance();
		shortcodeBusinessMyBody::Instance();
		shortcodeBusinessProfile::Instance();
		shortcodeBusinessAddress::Instance();
		shortcodeBusinessCertificate::Instance();
		UserAuthentication::Instance();
	}

	static function GetShowcaseInformation( array $attr ) {
		if ( $profileId = $attr['profileid'] ?? Util::get_profileId() ) {
			if ( isset( self::$Showcase[ $profileId ] ) ) {
				return self::$Showcase[ $profileId ];
			}

			if ( ( $GetShowcaseInformation = Showcase::Instance()->GetShowcaseInformation( $profileId ) ) && $GetShowcaseInformation instanceof HttpResponse ) {
				$GetShowcaseInformation = new dtoBusinessShowcase();
			}

			return self::$Showcase[ $profileId ] = $GetShowcaseInformation;
		}

		return new dtoBusinessShowcase();
	}

	/**
	 * @param array $atts
	 * @param array $fields
	 * @param mixed $data
	 *
	 * @return array
	 */
	static function Shortcode_Output( array $atts, array $fields, $data ): array {
		$output = [];

		$profileId = $attr['profileid'] ?? Util::get_profileId();
		if ( empty( $profileId ) ) {
			return $output;
		}

		//ToDo: Showcase remove completely
		$Permission             = new enumPermission( enumPermission::{$_REQUEST['Permission'] ?? 'Private'}() );
		$GetShowcaseInformation = self::GetShowcaseInformation( $atts );

		foreach ( array_filter( explode( ';', $atts['fields'] ) ) as $field ) {
			$arr    = explode( '|', $field );
			$field  = (string) current( $arr );
			$format = (string) ( $arr[1] ?? '' );
			if ( array_key_exists( $field, $fields ) ) {
				$value = null;
				if ( ( $data_func = sprintf( 'get_%s', Util::snake_case( $field ) ) ) && method_exists( $data, $data_func ) ) {
					$value = $data->$data_func();
				} else if ( ( $data_func = sprintf( 'is_%s', Util::snake_case( $field ) ) ) && method_exists( $data, $data_func ) ) {
					$value = __( $field, 'miqid-core' );
					if ( ! $data->$data_func() ) {
						$value = sprintf( __( 'Not %s', 'miqid-core' ), $value );
					}
				}

				if ( $value instanceof Enum ) {
					$value = _x( $value->getKey(), $field, 'miqid-core' );
				} else if ( $value instanceof DateTime ) {
					if ( empty( $format ) ) {
						if ( preg_match( '/DateTime|date_time/', $field ) ) {
							$format = Util::get_miqid_core_settings()->get_date_time_format();
						} else if ( preg_match( '/Time/', $field ) ) {
							$format = Util::get_miqid_core_settings()->get_time_format();
						} else {
							$format = Util::get_miqid_core_settings()->get_date_format();
						}
					}
					$value = date_i18n( $format, $value->getTimestamp(), false );
				} else if ( ! empty( $format ) ) {
					$value = sprintf( '%s%s', $value, $format );
				}

				//ToDo: Showcase remove completely
				// <editor-fold Desc="Showcase">
				$PermissionType = new enumPermission( enumPermission::Private );

				switch ( get_class( $data ) ) {
					case dtoPrivateProfile::class:
					case dtoBusinessProfile::class:
						if ( $profile_settings = $GetShowcaseInformation->get_settings()->get_profile_settings() ) {
							$PermissionType_func = sprintf( 'get_%s', Util::snake_case( $field ) );
							if ( method_exists( $profile_settings, $PermissionType_func ) ) {
								$PermissionType = $profile_settings->$PermissionType_func();
							}
						}
						break;
					case dtoPrivateMyBody::class:
					case dtoBusinessMyBody::class:
						if ( $my_body_settings = $GetShowcaseInformation->get_settings()->get_my_body_settings() ) {
							$PermissionType_func = sprintf( 'get_%s', Util::snake_case( $field ) );
							if ( method_exists( $my_body_settings, $PermissionType_func ) ) {
								$PermissionType = $my_body_settings->$PermissionType_func();
							}
						}
						break;
					case dtoPrivateDriversLicense::class:
					case dtoBusinessDriversLicense::class:
						if ( $certificate_settings = $GetShowcaseInformation->get_settings()->get_certificate_settings() ) {
							$PermissionType = $certificate_settings->get_drivers_licence();
						}
						break;
					case dtoPrivatePassport::class:
					case dtoBusinessPassport::class:
						if ( $certificate_settings = $GetShowcaseInformation->get_settings()->get_certificate_settings() ) {
							$PermissionType = $certificate_settings->get_passport();
						}
						break;
					case dtoBusinessHealthInsuranceCard::class:
						if ( $certificate_settings = $GetShowcaseInformation->get_settings()->get_certificate_settings() ) {
							$PermissionType = $certificate_settings->get_health_insurance();
						}
						break;
					case dtoPrivateAddress::class:
					case dtoBusinessAddress::class:
						if ( $profile_settings = $GetShowcaseInformation->get_settings()->get_profile_settings() ) {
							$PermissionType = $profile_settings->get_address();
						}
						break;
					case FileContentResult::class:
						if ( $FileContentResultType = $data->get_file_content_result_type() && $certificate_settings = $GetShowcaseInformation->get_settings()->get_certificate_settings() ) {
							switch ( $FileContentResultType ) {
								case FileContentResultType::PassportImage:
								case FileContentResultType::PassportFaceImage:
									$PermissionType = $certificate_settings->get_passport();
									break;
								case FileContentResultType::DriversLicenseImage:
								case FileContentResultType::DriversLicenseFaceImage:
									$PermissionType = $certificate_settings->get_drivers_licence();
									break;
								case FileContentResultType::HealthInsuranceCardImage:
									$PermissionType = $certificate_settings->get_health_insurance();
									break;
							}
						}
						break;

				}

				//ToDo: Remeber to disable Showcase
//				if ( $PermissionType->equals( enumPermission::NotSet ) ) {
//					$PermissionType = new enumPermission( enumPermission::Private );
//				}
//				if ( version_compare( $PermissionType, $Permission, '>' ) ) {
//					$value = sprintf(
//						_x( 'Data not shared with %s in MIQID.', 'PermissionType', 'miqid-core' ),
//						_x( $Permission->getKey(), 'PermissionType', 'miqid-core' ) );
//				}
				// </editor-fold>

				$output[ $field ] = $value;
			}
		}

		return $output;
	}

	/**
	 * @param array $attr
	 * @param FileContentResult|HttpResponse $data
	 *
	 * @return array|null
	 */
	static function Shortcode_Output_Image( array $attr, $data ): string {
		$output = '';

		$profileId = $attr['profileid'] ?? Util::get_profileId();
		if ( empty( $profileId ) ) {
			return $output;
		}

		$Permission             = new enumPermission(
			enumPermission::{$_REQUEST['Permission'] ?? 'Private'}() );
		$PermissionType         = clone $Permission;
		$GetShowcaseInformation = self::GetShowcaseInformation( $attr );
		if ( $data instanceof FileContentResult ) {
			$filename = implode( DIRECTORY_SEPARATOR, [
				Util::get_case_dir(),
				$data->get_file_content_result_type()->getValue(),
				$profileId,
			] );

			switch ( $data->get_content_type() ) {
				case 'image/jpg':
					$filename .= '.jpg';
					break;
				case 'image/png':
					$filename .= '.png';
					break;
				default:
					error_log( 'Extension not setup' );
					break;
			}

			$folder = dirname( $filename );
			if ( ! file_exists( $folder ) ) {
				wp_mkdir_p( $folder );
			}

			Util::clean_cache( $folder, 15 );

			if ( ! file_exists( $filename ) ) {
				if ( $img = imagecreatefromstring(
					base64_decode(
						$data->get_file_contents() ) ) ) {
					switch ( $data->get_content_type() ) {
						case 'image/jpg':
							imagejpeg( $img, $filename );
							imagedestroy( $img );
							break;
						case 'image/png':
							imagepng( $img, $filename );
							imagedestroy( $img );
							break;
					}
				}
			}

			$output = sprintf( '<img src="%s" />',
				strtr( $filename, [
					wp_upload_dir()['basedir'] => wp_upload_dir()['baseurl'],
				] ) );

			if ( ( $Settings = $GetShowcaseInformation->get_settings() ) && ( $CertificateSettings = $Settings->get_certificate_settings() ) ) {
				switch ( $data->get_file_content_result_type() ) {
					case FileContentResultType::PassportFaceImage:
					case FileContentResultType::PassportImage:
						$PermissionType = $CertificateSettings->get_passport();
						break;
					case FileContentResultType::DriversLicenseFaceImage:
					case FileContentResultType::DriversLicenseImage:
						$PermissionType = $CertificateSettings->get_drivers_licence();
						break;
					case FileContentResultType::HealthInsuranceCardImage:
						$PermissionType = $CertificateSettings->get_health_insurance();
						break;
				}
			}

			if ( version_compare( $PermissionType, $Permission, '>' ) ) {
				$output = sprintf( '<img src="%s/profile-picture-not-shared.png" />',
					Util::get_assets_images_url() );
			}

		} else if ( $data instanceof HttpResponse ) {
			$output = sprintf( '<img src="%s/no-profile-picture.jpg" />',
				Util::get_assets_images_url() );
		}

		return $output;
	}
}
<?php

namespace MIQID\Plugin\Core\Admin;

use MIQID\Plugin\Core\Util;

use MIQID\Plugin\Core\Classes\DTO\Business\{
	DriversLicense as business_DriversLicense,
	HealthInsuranceCard as business_HealthInsuranceCard,
	MyBody as business_MyBody,
	Passport as business_Passport,
	Profile as business_Profile,
	UserAddress as business_UserAddress
};
use ReflectionClass;

class Core {
	private static $instance;
	private $tab;
	private $tabs;
	private $base_uri;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		if ( ! defined( 'MIQID_ENDPOINT' ) ) {
			define( 'MIQID_ENDPOINT', false );
		}
		//TODO Finish CVR and CSS
		$this->tabs     = array_filter( [
			'Settings'   => _x( 'Settings', 'Settings', 'miqid-core' ),
			'Shortcodes' => _x( 'Shortcodes', 'Shortcodes', 'miqid-core' ),
			'Endpoints'  => MIQID_ENDPOINT ? _x( 'Endpoint', 'Endpoint', 'miqid-core' ) : null
			//'CVR'         => _x( 'CVR-validation', 'CVR', 'miqid-core' ),
			//'CSS'         => _x( 'CSS', 'CSS', 'miqid-core' ),
		] );
		$req            = array_change_key_case( $_REQUEST, CASE_LOWER );
		$this->tab      = in_array( $req['tab'] ?? false, array_keys( $this->tabs ) ) ? $req['tab'] : 'Settings';
		$this->base_uri = add_query_arg( [
			'tab' => null,
		], $_SERVER['REQUEST_URI'] );
	}

	function Page() {
		?>
        <div class="wrap miqid-admin core">
            <main>
                <div class="banner">
                </div>

				<?php self::Navigation(); ?>

				<?php self::{$this->tab}(); ?>
            </main>
			<?php Sidebar::Instance()->Sidebar(); ?>
        </div>
		<?php
	}

	function Navigation() {
		printf( '<nav class="nav-tab-wrapper">%1$s</nav>',
			implode( array_map( function ( $tab ) {
				$text = $this->tabs[ $tab ];

				return sprintf( '<a href="%3$s" class="nav-tab %2$s">%1$s</a>',
					$text,
					$this->tab === $tab ? 'nav-tab-active' : null,
					add_query_arg( [
						'tab'   => $tab,
						'class' => null,
					], $this->base_uri ) );
			}, array_keys( $this->tabs ) ) ) );
	}

	function Settings() {
		?>
        <section>
            <main>
                <table>
                    <tr>
                        <td colspan="2"><h3><?= _x( 'API', 'Settings', 'miqid-core' ) ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= _x( 'For at integrere MIQID på din hjemmeside skal din API-nøgle indtastes. Har du ikke en API-nøgle, kan du få en <a href="https://miqid.com">her</a>.', 'Settings', 'miqid-core' ) ?></td>
                    </tr>
                    <tr>
                        <th><label for="JWT"><?= _x( 'API-key', 'Settings', 'miqid-core' ) ?></label></th>
                        <td><input type="text" id="JWT" name="miqid-core[JWT]"
                                   value="<?= Util::get_miqid_core_settings()->get_JWT() ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><h3><?= _x( 'Skift bruger', 'Settings', 'miqid-core' ) ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= _x( 'Funktionen giver mulighed for at skifte mellem brugere uden at logge ud af administrator-rollen.', 'Settings', 'miqid-core' ) ?></td>
                    </tr>
                    <tr>
                        <th><label for="switch_user"><?= _x( 'Skift bruger', 'Settings', 'miqid-core' ) ?></label></th>
                        <td><input type="checkbox" id="switch_user"
                                   name="miqid-core[user_switching_enabled]" <?= Util::get_miqid_core_settings()->is_user_switching_enabled() ? 'checked' : null ?>>
                        </td>
                    </tr>
                    <!--<tr>
                        <td colspan="2"><h3><?/*= _x( 'Databasen', 'Settings', 'miqid-core' ) */ ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?/*= _x( 'Med MIQID har I mulighed for at erstatte brugerdatabasen med MIQID. Det betyder at I ikke længere er ansvarlige for håndteringen af brugernes personlige oplysninger, da de ligger hos MIQID.', 'Settings', 'miqid-core' ) */ ?></td>
                    </tr>
                    <tr>
                        <th><label for="replace_users"><?/*= _x( 'Erstat bruger database med MIQID', 'Settings', 'miqid-core' ) */ ?></label></th>
                        <td><input type="checkbox" id="replace_users" name="miqid-core[replace_users]" <?/*= Util::get_miqid_core_settings()->is_user_switching_enabled() ? 'checked' : null */ ?>></td>
                    </tr>-->
                </table>
                <div class="btn-group">
                    <button class="btn-save"><?= __( 'Save', 'miqid-core' ) ?></button>
                </div>
            </main>
        </section>
		<?php
	}

	function CVR() {
		?>
        <section>
            <main>
                <table>
                    <tr>
                        <td colspan="2"><h3><?= _x( 'CVR-validation', 'CVR', 'miqid-core' ) ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= _x( 'Indtast din CVR-valideringskode. Er I ikke allerede CVR-valideret kan i anmode om det <a href="https://miqid.com">her</a>.', 'CVR', 'miqid-core' ) ?></td>
                    </tr>
                    <tr>
                        <th><label for="cvr"><?= _x( 'CVR-validringskode', 'CVR', 'miqid-core' ) ?></label></th>
                        <td><input type="text" id="cvr" name="miqid-core[cvr]"
                                   value="<?= Util::get_miqid_core_settings()->get_cvr() ?>"></td>
                    </tr>
                </table>
            </main>
        </section>
		<?php
	}

	function CSS() {
		?>
        <section>
            <main>
                <h3><?= _x( 'CSS', 'CSS', 'miqid-core' ) ?></h3>
                <p><?= _x( 'Vælg jeres identitetsfarver, der bruges som standart.', 'CSS', 'miqid-core' ) ?></p>
                <div class="colors">
					<?php
					_x( 'Primary', 'CSS', 'miqid-color' );
					_x( 'Secondary', 'CSS', 'miqid-color' );
					$miqidCSS = get_option( 'miqid-css', [
						'colors' => [
							'Primary'   => '#000000',
							'Secondary' => '#B5B5B5',
						],
					] );
					foreach ( $miqidCSS['colors'] as $name => $value ) {
						?>
                        <div class="color">
                            <label for="color_<?= $name ?>>"><?= _x( $name, 'CSS', 'miqid-core' ) ?></label>
                            <input type="color" id="color_<?= $name ?>" name="miqid-css[colors][<?= $name ?>]"
                                   value="<?= $value ?>">
                            <span><?= $value ?></span>
                        </div>
					<?php } ?>
                </div>
                <div class="btn-group">
                    <button type="button"><?= _x( 'Add color', 'CSS', 'miqid-core' ) ?></button>
                </div>
                <p><?= _x( 'Vælg jeres skrifttype, der bruges som standart.', 'CSS', 'miqid-core' ) ?></p>
                <table>
                    <tr>
                        <th><?= _x( 'Family', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[font-family]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Size', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[font-size]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Weight', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[font-weight]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Transform', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[text-transform]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Style', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[text-style]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Decoration', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[text-decoration]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Line height', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[line-height]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th><?= _x( 'Letter spacing', 'CSS', 'miqid-core' ) ?></th>
                        <td><select name="miqid-css[letter-spacing]">
                                <option>Standard</option>
                            </select></td>
                    </tr>
                </table>
            </main>
        </section>
		<?php
	}

	function Shortcodes() {
		$add_query_arg  = add_query_arg( [ 'tab' => __FUNCTION__, 'class' => null, ], $_SERVER['REQUEST_URI'] );
		$selected_class = base64_decode( $_GET['class'] ?? '' );

		$Endpoints = [
			'Business' => [
				business_Profile::class,
				business_UserAddress::class,
				business_MyBody::class,
				business_Passport::class,
				business_DriversLicense::class,
				business_HealthInsuranceCard::class,
			],
			'Custom'   => 'Custom',
		];
		foreach ( $Endpoints as $nav => $classes ) {
			?>
            <nav class="nav-tab-wrapper">
				<?php

				printf( '<a href="%s" class="nav-tab %s">%s</a>',
					add_query_arg( [
						'class' => base64_encode( $nav ),
					], $add_query_arg ),
					$nav == $selected_class ? 'nav-tab-active' : null,
					$nav );
				if ( is_array( $classes ) ) {
					foreach ( $classes as $class ) {
						$ShortName = $class;
						if ( class_exists( $class ) ) {
							$ReflectionClass = new ReflectionClass( $class );
							$ShortName       = $ReflectionClass->getShortName();
						}

						printf( '<a href="%s" class="nav-tab %s">%s</a>',
							add_query_arg( [
								'class' => base64_encode( $class ),
							], $add_query_arg ),
							$class == $selected_class ? 'nav-tab-active' : null,
							$ShortName );
					}
				}
				?>
            </nav>
			<?php
		} ?>
        <hr class="wp-header-end"/>
		<?php
		$ShortCodes = [];
		if ( ( $class = $selected_class ) && class_exists( $class ) ) {

			$short_name      = mb_strtolower( strtr( $class, [
				'MIQID\\Plugin\\Core\\Classes\\DTO\\' => '',
				'\\'                                  => '-',
			] ) );
			$ReflectionClass = new ReflectionClass( $class );
			do {
				foreach ( $ReflectionClass->getProperties() as $property ) {
					$ShortCodes[] = sprintf( '[miqid-%1$s fields="%2$s"%3$s]',
						$short_name,
						$property->getName(),
						mb_strpos( $short_name, 'business' ) !== false ? sprintf( " profileid=\"%s\"", Util::get_profileId() ) : null );
				}
			} while ( $ReflectionClass = $ReflectionClass->getParentClass() );
		} elseif ( $selected_class == 'Custom' ) {
			$ShortCodes[] = '[miqid-login]';

			$ShortCodes[] = '[miqid-business-passportimage]';
			$ShortCodes[] = '[miqid-business-passportfaceimage]';

			$ShortCodes[] = '[miqid-business-driverslicenseimage]';
			$ShortCodes[] = '[miqid-business-driverslicensefaceimage]';

			$ShortCodes[] = '[miqid-business-healthinsurancecardimage]';
		}
		?>
        <table style="width: 100%; border-spacing: 0">
            <thead>
            <tr>
                <th style="text-align: left">ShortCode</th>
                <th style="text-align: left">Preview</th>
            </tr>
            </thead>
			<?php
			foreach ( $ShortCodes as $ShortCode ) {
				$cssClass = preg_replace( '/\[([\w\-]+) fields="(\w+)" [^\]]+]/m', '$1-$2', $ShortCode );
				printf( '<tr>
    <td style="white-space: nowrap; padding: 5px; border-bottom: 1px solid #303030">%1$s</td>
    <td style="width: 75%%; padding: 5px; border-bottom: 1px solid #303030" class="%3$s">%2$s</td>
</tr>',
					$ShortCode,
					do_shortcode( $ShortCode ),
					mb_strtolower( $cssClass ) );
			}
			?>
        </table>
		<?php

	}

	function Endpoints() {
		?>
        <section>
            <main>
                <table>
                    <tr>
                        <td colspan="2"><h3><?= _x( 'API', 'Settings', 'miqid-core' ) ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= _x( 'For at integrere MIQID på din hjemmeside skal din API-nøgle indtastes. Har du ikke en API-nøgle, kan du få en <a href="https://miqid.com">her</a>.', 'Settings', 'miqid-core' ) ?></td>
                    </tr>
                    <tr>
                        <th><label for="JWT"><?= _x( 'API-key', 'Settings', 'miqid-core' ) ?></label></th>
                        <td><input type="text" id="JWT" name="miqid-core[Endpoint_Business][Host]"
                                   value="<?= Util::get_miqid_core_settings()->get_endpoint_business()->get_host() ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="switch_user"><?= _x( 'Skift bruger', 'Settings', 'miqid-core' ) ?></label></th>
                        <td><input type="text" id="switch_user"
                                   name="miqid-core[Endpoint_Business][Version]" value="<?= Util::get_miqid_core_settings()->get_endpoint_business()->get_version() ?>">
                        </td>
                    </tr>
                    <!--<tr>
                        <td colspan="2"><h3><?/*= _x( 'Databasen', 'Settings', 'miqid-core' ) */ ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?/*= _x( 'Med MIQID har I mulighed for at erstatte brugerdatabasen med MIQID. Det betyder at I ikke længere er ansvarlige for håndteringen af brugernes personlige oplysninger, da de ligger hos MIQID.', 'Settings', 'miqid-core' ) */ ?></td>
                    </tr>
                    <tr>
                        <th><label for="replace_users"><?/*= _x( 'Erstat bruger database med MIQID', 'Settings', 'miqid-core' ) */ ?></label></th>
                        <td><input type="checkbox" id="replace_users" name="miqid-core[replace_users]" <?/*= Util::get_miqid_core_settings()->is_user_switching_enabled() ? 'checked' : null */ ?>></td>
                    </tr>-->
                </table>
                <div class="btn-group">
                    <button class="btn-save"><?= __( 'Save', 'miqid-core' ) ?></button>
                </div>
            </main>
        </section>
		<?php
	}
}
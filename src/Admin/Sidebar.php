<?php

namespace MIQID\Plugin\Core\Admin;

class Sidebar {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	function Sidebar() {
		add_thickbox();
		?>
        <aside class="miqid-sidebar">
            <section>
                <picture>

                </picture>
                <main>
                    <h3>Få fuldt udbytte af MIQID</h3>
                    <p>Vi har forskellige plugins, der kan hjælpe dig i hverdagen</p>
                    <ul>
                        <li><a href="<?= esc_attr( esc_url( add_query_arg( [ 'width' => 800, 'height' => 768 ], 'https://miqid-core.lea.karlog-it.dk/wp-admin/plugin-install.php?tab=plugin-information&plugin=miqid-elementor&TB_iframe=true' ) ) ) ?> " class="thickbox open-plugin-details-modal">MIQID-Elementor</a></li>
                        <li><a href="<?= esc_attr( esc_url( add_query_arg( [ 'width' => 800, 'height' => 768 ], 'https://miqid-core.lea.karlog-it.dk/wp-admin/plugin-install.php?tab=plugin-information&plugin=miqid-woo&TB_iframe=true' ) ) ) ?> " class="thickbox open-plugin-details-modal">MIQID-Woo</a></li>
                    </ul>
                </main>
            </section>
            <section>
                <main>
                    <h3>Support</h3>
                    <p>Få hjælp og find informationer</p>
                    <ul>
                        <li><a href="https://miqid.com/kontakt-os/" target="_blank">Kontakt os</a></li>
                        <li><a href="https://miqid.com/videnscenter/faq/" target="_blank">FAQ</a></li>
                        <li><a href="https://miqid.com/videnscenter/help-desk/" target="_blank">Opret supportsag</a></li>
                    </ul>
                </main>
            </section>
        </aside>
		<?php
	}
}
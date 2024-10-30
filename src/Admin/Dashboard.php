<?php

namespace MIQID\Plugin\Core\Admin;

class Dashboard {
	private static $instance;

	static function Instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	function Page() {
		?>
        <div class="wrap miqid-admin dashboard">
            <main>
                <header class="banner">
                </header>

                <!--<section class="bg-white">
                    <main>
                        <h3><?/*= _x( 'CVR-validated', 'CVR', 'miqid-core' ) */ ?></h3>
                        <table>
                            <tr>
                                <th><?/*= _x( 'CVR-number', 'CVR', 'miqid-core' ) */ ?></th>
                                <td>[CVR-nummer]</td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Company Name', 'CVR', 'miqid-core' ) */ ?></th>
                                <td>[Virksomhedsnavn]</td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Date of Approval', 'CVR', 'miqid-core' ) */ ?></th>
                                <td>[Dato for godkendelse]</td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Last modified', 'CVR', 'miqid-core' ) */ ?></th>
                                <td>[Sidst opdateret]</td>
                            </tr>
                        </table>
                    </main>
                    <aside>

                    </aside>
                </section>

                <section class="bg-white">
                    <main>
                        <h3><?/*= _x( 'CVR-validated', 'CVR', 'miqid-core' ) */ ?></h3>
                        <p>Når i gerne vil bruge MIQID kræver det, at I bliver godkendt, da vi holder jeres oplysninger op mod CVR-registeret. I kan nemt udfylde formularen nedenfor, og vi vender tilbage hurtigst muligt.</p>
                        <table>
                            <tr>
                                <th><?/*= _x( 'CVR-number', 'CVR', 'miqid-core' ) */ ?></th>
                                <td><input type="text"></td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Name on contact person', 'CVR', 'miqid-core' ) */ ?></th>
                                <td><input type="text"></td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Telephone number on contact person', 'CVR', 'miqid-core' ) */ ?></th>
                                <td><input type="text"></td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'Email on contact person', 'CVR', 'miqid-core' ) */ ?></th>
                                <td><input type="text"></td>
                            </tr>
                            <tr>
                                <th><?/*= _x( 'URL on company homepage', 'CVR', 'miqid-core' ) */ ?></th>
                                <td><input type="text"></td>
                            </tr>
                        </table>
                        <div class="btn-group">
                            <button>Send</button>
                        </div>
                    </main>
                </section>-->

            </main>
			<?php Sidebar::Instance()->Sidebar(); ?>
        </div>
		<?php
	}
}
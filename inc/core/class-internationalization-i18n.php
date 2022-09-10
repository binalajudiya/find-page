<?php

namespace Find_Page\Inc\Core;

/**
 * Define the internationalization functionality.
 *
 * @link       https://www.nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author     Binal Ajudiya
 */
class Internationalization_i18n {

	private $text_domain;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_text_domain ) {

		$this->text_domain = $plugin_text_domain;

	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->text_domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}

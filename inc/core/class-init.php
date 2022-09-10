<?php

namespace Find_Page\Inc\Core;
use Find_Page as NS;
use Find_Page\Inc\Admin as Admin;

/**
 * The core plugin class.
 * Defines internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * @since      1.0.0
 *
 * @author     Binal Ajudiya
 */
class Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_basename;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * The text domain of the plugin.
	 */
	protected $plugin_text_domain;


	// define the core functionality of the plugin.
	public function __construct() {

		$this->plugin_name = NS\PLUGIN_NAME;
		$this->version = NS\PLUGIN_VERSION;
				$this->plugin_basename = NS\PLUGIN_BASENAME;
				$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Loads the following required dependencies for this plugin.
	 */
	private function load_dependencies() {
		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_i18n( $this->plugin_text_domain );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//Add a top-level admin menu for our plugin
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		
		
		//when a form is submitted to admin-ajax.php
		$this->loader->add_action( 'wp_ajax_fp_form_response', $plugin_admin, 'the_form_response');
		
		// Register admin notices
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'print_plugin_admin_notices');
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_plugin_text_domain() {
		return $this->plugin_text_domain;
	}	

}

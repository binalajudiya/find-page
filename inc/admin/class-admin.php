<?php

namespace Find_Page\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 *
 * @author    Binal Ajudiya
 */
class Admin {
	private $plugin_name;
	private $version;
	private $plugin_text_domain;
	
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 * 
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/find-page-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 * 
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		$params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'fp_ajax_handle', plugin_dir_url( __FILE__ ) . 'js/find-page-ajax-handler.js', array( 'jquery' ), $this->version, false );				
		wp_localize_script( 'fp_ajax_handle', 'params', $params );		

	}
	
	/**
	 * Callback for the admin menu
	 * 
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		$ajax_form_page_hook = add_pages_page( 
									__( 'Find Page', $this->plugin_text_domain ),
									__( 'Find Page', $this->plugin_text_domain ),
									'manage_options',
									$this->plugin_name . '-ajax',
									array( $this, 'find_page_content' )
									);
	}
	
	public function find_page_content() {
		include_once( 'views/find_page_content.php' );
	}	
	
	public function the_form_response() {
		
		if( isset( $_POST['fp_add_user_meta_nonce'] ) && wp_verify_nonce( $_POST['fp_add_user_meta_nonce'], 'fp_add_user_meta_form_nonce') ) {
			$fp_slug = sanitize_key( $_POST['fp']['slug'] );
			$slug = str_replace("/", "",$fp_slug);
			$page = get_page_by_path( $slug );
			//echo "<pre>";
			//print_r($page);

			if( isset( $_POST['ajaxrequest'] ) && $_POST['ajaxrequest'] === 'true' ) {
				
				if ($page) {
					$edit_url =  get_site_url()."/wp-admin/post.php?post=".$page->ID."&action=edit";
					$data = '<div class="fp__result_wrap"><table class="fp__result_list"><tr><td class="fp__list_title">ID</td><td class="fp__list_value">'.$page->ID.'</td></tr>
					<tr><td class="fp__list_title">Name</td><td class="fp__list_value">'.$page->post_title.'</td></tr>
					<tr><td class="fp__list_title">Slug</td><td class="fp__list_value">'.$page->post_name.'</td></tr>
					<tr><td class="fp__list_title">Edit Page</td><td class="fp__list_value"><a href="'.$edit_url.'">Edit Link</a></td></tr></table></div>';
				}
				else {
					$data = '<div class="fp__result_wrap"><p class="fp_notfound">Sorry, no page exits with that name</p></div>';
				}
				print_r($data);				
				wp_die();
            }

			$admin_notice = "success";
			$this->custom_redirect( $admin_notice, $data );
			exit;
		}			
		else {
			wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
						'response' 	=> 403,
						'back_link' => 'admin.php?page=' . $this->plugin_name,

				) );
		}
	}

	public function custom_redirect( $admin_notice, $response ) {
		wp_redirect( esc_url_raw( add_query_arg( array(
									'fp_admin_add_notice' => $admin_notice,
									'fp_response' => $response,
									),
							admin_url('admin.php?page='. $this->plugin_name ) 
					) ) );
	}


	/**
	 * Print Admin Notices
	 * 
	 * @since    1.0.0
	 */
	public function print_plugin_admin_notices() {              
		  if ( isset( $_REQUEST['fp_admin_add_notice'] ) ) {
			if( $_REQUEST['fp_admin_add_notice'] === "success") {
				$html =	'<div class="notice notice-success is-dismissible"> 
							<p><strong>The request was successful. </strong></p><br>';
				$html .= '<pre>' . htmlspecialchars( print_r( $_REQUEST['fp_response'], true) ) . '</pre></div>';
				echo $html;
			}

		  }
		  else {
			  return;
		  }

	}


}
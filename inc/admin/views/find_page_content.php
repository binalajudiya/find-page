<?php
if (!defined('ABSPATH') && !current_user_can('manage_options')) {
    exit;
} 
// Generate a custom nonce value.
$fp_add_meta_nonce = wp_create_nonce( 'fp_add_user_meta_form_nonce' ); 	
?>	

<div class="findpage__main">
	<div class="findpage__wrap">
		<div class="fp__title">
			<h1> Find page by slug </h1>
		</div>
		
		<div class="fp__instruction">
			<p>-> Enter a page slug name without domain name </p>
			<p>-> I.e. If the page URL is https://domain.com/contact then add contact to below input</p>
		</div>

		<div class="fp__form_wrap">
			<div class="fp__add_user_meta_form">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="fp_add_user_meta_ajax_form" class="fp__form">			
					<input type="hidden" name="action" value="fp_form_response">
					<input type="hidden" name="fp_add_user_meta_nonce" value="<?php echo $fp_add_meta_nonce ?>" />			
					<div class="fp__input">
						<input required id="<?php echo $this->plugin_name; ?>-slug" type="text" name="<?php echo "fp"; ?>[slug]" value="" placeholder="<?php _e('Enter a Slug', $this->plugin_name);?>" class="fp__input_field"/>
						<input type="submit" class="fp__submit" name="submit" id="submit" class="button button-primary" value="Submit">
					</div>
				</form>
			</div>
		</div>

		<div class="fp__result">
		</div>
	</div>
</div>

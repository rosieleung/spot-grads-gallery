<?php
/*
Plugin Name: SPOT Grads Gallery
Version:     1.0.0
Plugin URI:  https://rosieleung.com/
Description: Adds a gallery to display SPOT graduates.
Author:      Rosie Leung
Author URI:  mailto:rosie@rosieleung.com
*/

defined( 'ABSPATH' ) or die();

define( 'SPOT_GRADS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SPOT_GRADS_PATH', dirname( __FILE__ ) );
define( 'SPOT_GRADS_VERSION', '1.0.0' );

function spot_grads_init_plugin() {
	// js and css
	include_once( SPOT_GRADS_PATH . '/includes/enqueue.php' );
	
	// register post type and taxonomy and handle templating
	include_once( SPOT_GRADS_PATH . '/includes/spot-grad-post-type.php' );
	
	// add widget and functions to output and display individual dogs
	include_once( SPOT_GRADS_PATH . '/includes/spot-grad-functions.php' );
}

add_action( 'plugins_loaded', 'spot_grads_init_plugin' );

function spot_grads_activate() {
	include_once( SPOT_GRADS_PATH . '/includes/spot-grad-post-type.php' );
	spot_grads_register_post_type();
	
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'spot_grads_activate' );

function spot_grads_deactivate() {
	unregister_post_type( 'spot_grad' );
	unregister_taxonomy( 'spot_grad_breed' );
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'spot_grads_deactivate' );
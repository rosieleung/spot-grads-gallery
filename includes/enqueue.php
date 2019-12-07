<?php

defined( 'ABSPATH' ) or die();


function spot_grads_enqueue_scripts() {
	wp_enqueue_style( 'spot-grads', SPOT_GRADS_URL . '/assets/spot-grads.css', array(), SPOT_GRADS_VERSION );
	wp_enqueue_script( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array( 'jquery' ), SPOT_GRADS_VERSION );
	wp_enqueue_script( 'spot-grads', SPOT_GRADS_URL . '/assets/spot-grads.js', array( 'jquery', 'isotope' ), SPOT_GRADS_VERSION );
}

add_action( 'wp_enqueue_scripts', 'spot_grads_enqueue_scripts' );

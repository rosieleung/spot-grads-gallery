<?php

defined( 'ABSPATH' ) or die();

// register spot_grad custom post type

function spot_grads_register_post_type() {
	
	$labels = array(
		'name'                  => 'SPOT Grads',
		'singular_name'         => 'SPOT Grad',
		'menu_name'             => 'SPOT Grads',
		'name_admin_bar'        => 'SPOT Grad',
		'archives'              => 'SPOT Grad Archives',
		'attributes'            => 'SPOT Grad Attributes',
		'parent_item_colon'     => 'Parent SPOT Grad:',
		'all_items'             => 'All SPOT Grads',
		'add_new_item'          => 'Add New SPOT Grad',
		'add_new'               => 'Add New',
		'new_item'              => 'New SPOT Grad',
		'edit_item'             => 'Edit SPOT Grad',
		'update_item'           => 'Update SPOT Grad',
		'view_item'             => 'View SPOT Grad',
		'view_items'            => 'View SPOT Grads',
		'search_items'          => 'Search SPOT Grad',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into SPOT grad',
		'uploaded_to_this_item' => 'Uploaded to this SPOT grad',
		'items_list'            => 'SPOT grads list',
		'items_list_navigation' => 'SPOT grads list navigation',
		'filter_items_list'     => 'Filter SPOT grads list',
	);
	
	$args = array(
		'label'               => 'SPOT Grad',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'          => array( 'spot_grad_breed' ),
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-image',
		'hierarchical'        => false,
		'public'              => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'rewrite'             => array( 'with_front' => false, 'slug' => 'spot-grads' ),
	);
	register_post_type( 'spot_grad', $args );
}

add_action( 'init', 'spot_grads_register_post_type', 5 );


// register spot_grad_breed taxonomy for spot_grad post type

function spot_grads_register_breed_taxonomy() {
	
	$labels = array(
		'name'                       => 'Breeds',
		'singular_name'              => 'Breed',
		'menu_name'                  => 'Breeds',
		'all_items'                  => 'All Breeds',
		'parent_item'                => 'Parent Breed',
		'parent_item_colon'          => 'Parent Breed:',
		'new_item_name'              => 'New Breed Name',
		'add_new_item'               => 'Add New Breed',
		'edit_item'                  => 'Edit Breed',
		'update_item'                => 'Update Breed',
		'view_item'                  => 'View Breed',
		'separate_items_with_commas' => 'Separate breeds with commas',
		'add_or_remove_items'        => 'Add or remove breeds',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Breeds',
		'search_items'               => 'Search Breeds',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No breeds',
		'items_list'                 => 'Breed list',
		'items_list_navigation'      => 'Breed list navigation',
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => false,
	);
	register_taxonomy( 'spot_grad_breed', array( 'spot_grad' ), $args );
	
}

add_action( 'init', 'spot_grads_register_breed_taxonomy', 0 );

// add a meta box to spot_grad edit pages that allows the dog to be hidden from the random dog rotator

function spot_grads_add_meta_boxes() {
	add_meta_box(
		'hide-in-breed-rotator',
		'Hide in dog rotator',
		'spot_grads_hide_in_breed_rotator_meta_box',
		'spot_grad',
		'normal',
		'low'
	);
}

add_action( 'add_meta_boxes', 'spot_grads_add_meta_boxes' );

// form for the meta box

function spot_grads_hide_in_breed_rotator_meta_box( $object ) {
	wp_nonce_field( SPOT_GRADS_PATH, "hide_in_rotator_nonce" );
	$checked = get_post_meta( $object->ID, "hide_in_rotator", true ) ? ' checked' : '';
	?>
	<label>
		<input name="hide_in_rotator" type="checkbox" value="1"<?php echo $checked; ?> /> Hide in rotator?
	</label>
	<?php
}

// save the meta box

function spot_grads_save_hide_in_breed_rotator_meta_box( $post_id, $post, $update ) {
	if ( ! isset( $_POST["hide_in_rotator_nonce"] ) || ! wp_verify_nonce( $_POST["hide_in_rotator_nonce"], SPOT_GRADS_PATH ) ) {
		return $post_id;
	}
	
	if ( ! current_user_can( "edit_post", $post_id ) ) {
		return $post_id;
	}
	
	if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	
	if ( $post->post_type != "spot_grad" ) {
		return $post_id;
	}
	
	$meta_box_checkbox_value = isset( $_POST["hide_in_rotator"] ) ? $_POST["hide_in_rotator"] : "";
	
	update_post_meta( $post_id, "hide_in_rotator", $meta_box_checkbox_value );
}

add_action( "save_post", "spot_grads_save_hide_in_breed_rotator_meta_box", 10, 3 );

// use the custom archive template for the spot_grad archive page

function spot_grads_custom_archive_template( $archive_template ) {
	if ( is_post_type_archive( 'spot_grad' ) ) {
		$archive_template = SPOT_GRADS_PATH . '/templates/archive-spot_grad.php';
	}
	
	return $archive_template;
}

add_filter( 'archive_template', 'spot_grads_custom_archive_template' );

// use the custom single post template for spot_grad posts

function spot_grads_custom_single_template( $single_template ) {
	if ( is_singular( 'spot_grad' ) ) {
		$single_template = SPOT_GRADS_PATH . '/templates/single-spot_grad.php';
	}
	
	return $single_template;
}

add_filter( 'single_template', 'spot_grads_custom_single_template' );

// show all spot grads on the archive page

function spot_grads_set_posts_per_page_for_dog_gallery_archive( $query ) {
	if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'spot_grad' ) ) {
		$query->set( 'posts_per_page', '-1' );
	}
}

add_action( 'pre_get_posts', 'spot_grads_set_posts_per_page_for_dog_gallery_archive' );

// add a settings page to the spot_grad admin menu

if ( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page( array(
		'page_title'  => 'Gallery Settings',
		'menu_title'  => 'Gallery Settings',
		'parent_slug' => 'edit.php?post_type=spot_grad',
	) );
}

// add an acf field group to the spot_grad admin menu

if ( function_exists( 'acf_add_local_field_group' ) ):
	
	acf_add_local_field_group( array(
		'key'                   => 'group_5c721cceef3a8',
		'title'                 => 'SPOT Grad Gallery Settings',
		'fields'                => array(
			array(
				'key'               => 'field_5c721cdc172e7',
				'label'             => 'Intro text',
				'name'              => 'spot_grads_intro_text',
				'type'              => 'wysiwyg',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'tabs'              => 'all',
				'toolbar'           => 'full',
				'media_upload'      => 0,
				'delay'             => 0,
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'acf-options-gallery-settings',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => 1,
		'description'           => '',
	) );

endif;
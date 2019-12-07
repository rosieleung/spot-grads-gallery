<?php

defined( 'ABSPATH' ) or die();

// returns the ID of a random spot grad, excluding dogs that are hidden
function spot_grads_get_random_dog_id() {
	$query_args = array(
		'fields'         => 'ids',
		'post_type'      => 'spot_grad',
		'orderby'        => 'rand',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'     => '_thumbnail_id',
				'value'   => '',
				'compare' => '!=',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'hide_in_rotator',
					'value'   => '1',
					'compare' => '!=',
				),
				array(
					'key'     => 'hide_in_rotator',
					'compare' => 'NOT EXISTS',
				),
			),
		),
	);
	
	$query = new WP_Query( $query_args );
	
	if ( ! $query->have_posts() ) {
		return false;
	} else {
		return $query->posts[0];
	}
}

// outputs a spot grad given its post ID
function spot_grads_display_dog( $spot_grads_id, $size = 'medium' ) {
	$title    = get_the_title( $spot_grads_id );
	$image_id = get_post_thumbnail_id( $spot_grads_id );
	$image    = wp_get_attachment_image( $image_id, $size );
	
	?>
	<div class="single-spot-grads-dog">
		<div class="single-spot-grad-photo">
			<a href="<?php echo get_permalink( $spot_grads_id ); ?>" title="Read more about <?php echo $title; ?>">
				<?php echo $image; ?>
			</a>
		</div>
		<div class="single-spot-grad-name-wrapper">
			<h3><a href="<?php echo get_permalink( $spot_grads_id ); ?>" title="Read more about <?php echo $title; ?>">
					<?php echo $title; ?>
				</a></h3>
			<h4><a href="<?php echo get_post_type_archive_link( 'spot_grad' ); ?>">More SPOT Grads &rarr;</a></h4>
		</div>
	</div>
	<?php
}

// widget to display a random dog
class DogGalleryWidget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'dog-gallery-widget',
			'description' => 'Displays a photo and caption of a random dog from the SPOT Grads section.',
		);
		parent::__construct( 'dog-gallery-widget', 'Dog Gallery', $widget_ops );
	}
	
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		
		// outputs the content of the widget
		$spot_grads_id = spot_grads_get_random_dog_id();
		if ( ! $spot_grads_id ) {
			return;
		}
		
		// ----
		// Display the widget
		
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . '<a href="' . get_post_type_archive_link( 'spot_grad' ) . '">' . apply_filters( 'widget_title', $instance['title'] ) . '</a>' . $args['after_title'];
		}
		
		spot_grads_display_dog( $spot_grads_id );
		
		echo $args['after_widget'];
	}
	
	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = array(
			'value' => isset( $instance['title'] ) ? $instance['title'] : 'Dog Gallery',
			'id'    => $this->get_field_id( 'title' ),
			'name'  => $this->get_field_name( 'title' ),
			'label' => __( 'Title:', 'causes' ),
		);
		
		?>
		<p>
			<label for="<?php echo esc_attr( $title['id'] ); ?>"><?php echo esc_attr( $title['label'] ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $title['id'] ); ?>" name="<?php echo esc_attr( $title['name'] ); ?>" value="<?php echo esc_attr( $title['value'] ); ?>">
		</p>
		<?php
	}
	
	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance          = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? $new_instance['title'] : '';
		
		return $instance;
	}
}

// register the random spot grad widget
function register_spot_grads_widget() {
	register_widget( 'DogGalleryWidget' );
}

add_action( 'widgets_init', 'register_spot_grads_widget' );

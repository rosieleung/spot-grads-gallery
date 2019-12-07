<?php get_header(); ?>
	
	<div id="content" class="content">
		<div class="section-page-title">
			<div class="container">
				<div class="gutter">
					<h2>SPOT GRADS</h2>
				</div>
			</div>
		</div>
		
		<div class="section-inner-page">
			<div class="container">
				<div class="gutter">
					<div class="page-container">
						
						<?php if ( $content = get_field( "spot_grads_intro_text", "options" ) ) : ?>
							<div class="spot-grads-intro-text">
								<?php echo $content; ?>
							</div>
						<?php endif; ?>
						
						<div class="spot-grads-sorting-filtering">
							<div class="spot-grads-sorting">
								Sort by:
								<select id="spot-grads-sorting">
									<option data-ascending="true" value="original-order">Date added (newest first)</option>
									<option data-ascending="false" value="original-order">Date added (oldest first)</option>
									<option data-ascending="true" value="name">Name (A–Z)</option>
									<option data-ascending="false" value="name">Name (Z–A)</option>
								</select>
							</div>
							
							<div class="spot-grads-filtering">
								Show dogs tagged:
								<select id="spot-grads-filtering">
									<option value="*">All breeds and mixes (<?php echo $wp_the_query->post_count; ?>)</option>
									<?php
									$terms = get_terms( array(
										'taxonomy'   => 'spot_grad_breed',
										'hide_empty' => true,
									) );
									foreach ( $terms as $term ) : ?>
										<option value=".<?php echo $term->slug; ?>"><?php echo $term->name; ?> (<?php echo $term->count; ?>)</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						
						
						<div id="spot-grads-gallery">
							<?php while( have_posts() ) : the_post();
								$classes = '';
								if ( $terms = get_the_terms( get_the_ID(), 'spot_grad_breed' ) ) {
									foreach ( $terms as $term ) {
										$classes .= ' ' . $term->slug;
									}
								}
								?>
								<a class="spot-grads-dog<?php echo $classes; ?>" href="<?php echo get_permalink(); ?>">
									<div class="spot-grad-photo">
										<?php the_post_thumbnail( 'thumbnail' ); ?>
									</div>
									<div class="spot-grad-name-wrapper">
										<h3 class="spot-grad-name"><?php the_title(); ?></h3>
									</div>
								</a>
							<?php endwhile; ?>
						</div>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
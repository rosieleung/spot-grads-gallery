<?php
/**
 * The template for displaying all pages.
 * @package Causes
 */
get_header(); ?>
<?php while( have_posts() ) : the_post(); ?>
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
				<div class="column-container">
					<div class="column-12-12">
						<div class="gutter">
							<div class="page-container">
								<article class="article-single">
									<p class="breadcrumbs"><a href="<?php echo get_post_type_archive_link( 'spot_grad' ); ?>">SPOT Grads</a> &raquo; <?php the_title(); ?></p>
									<h3><?php the_title(); ?></h3>
									<p class="meta">
										Posted on <?php the_time( get_option( 'date_format' ) ); ?>
										<?php
										if ( $terms = get_the_terms( get_the_ID(), 'spot_grad_breed' ) ):
											$tags = array();
											foreach ( $terms as $term ) {
												$tags[] = $term->name;
											}
											echo ' | Tags: ', implode( ', ', $tags );
										endif;
										?>
									</p>
									<?php the_content(); ?>
									
									
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="article-image">
											<a href="<?php the_post_thumbnail_url( 'fullsize' ); ?>">
												<?php the_post_thumbnail( 'large' ); ?>
											</a>
										</div>
									<?php endif; ?>
								
								</article>
							</div>
						</div>
					</div>
					<?php /*
						<div class="column-3-12 right">
							<div class="gutter">
								<?php  get_sidebar(); ?>
							</div>
						</div>
						*/ ?>
				</div>
			</div>
		</div>
	</div>
<?php endwhile; ?>
<?php get_footer(); ?>
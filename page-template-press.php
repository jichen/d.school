<?php
/**
 * Template Name: Press
 *
 * A custom page template for the press page
 *
 * @package dschool
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // dschool_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // dschool_open_content ?>

		<div class="hfeed">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // dschool_before_entry ?>

					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<?php do_atomic( 'open_entry' ); // dschool_open_entry ?>

						<h1 class="entry-title"><span><?php the_title(); ?></span></h1>

						<?php if (get_post_meta( $post->ID, 'Quote', true )) {echo '<blockquote class="feature-quote">&#8220;' . get_post_meta( $post->ID, 'Quote', true ) . "&#8221;</blockquote>";} ?>

						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', hybrid_get_textdomain() ) ); ?>

							<div id="press-grid">

							<?php $temp_query = clone $wp_query; ?>
					
							<?php $counter = 1; ?>
												
							<?php query_posts('post_type=press&posts_per_page=-1'); ?>
							
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
								<?php if (($counter == 1) || (($counter-1) % 4) == 0) { $closed = 0; ?><div class="panel-row"><?php } ?>		
							
								<div class="press-panel <?php if (($counter % 4) == 0) {echo 'panel-end';}?> <?php echo "panel-" . $counter; ?>">
								
								<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'medium', 'image_class' => 'press-thumb', 'link_to_post' => false ) ); ?>
					
								<h2 class="press-title"><?php the_title(); ?></h2>
								<div class="press-content">
								<?php the_content() ?>
								</div>
								</div><!-- .press-panel -->
					
								<?php if (($counter % 4) == 0) { $closed = 1; ?></div><!-- panel-row --><?php } ?>
					
								<?php $counter++; ?>
					
							<?php endwhile; endif; ?>
							
							<?php if ($closed == 0) { ?></div><!-- panel-row --><?php } ?>
							   
							<?php $wp_query = clone $temp_query; ?>

							</div><!-- press-grid -->

						</div><!-- .entry-content -->
						
						<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[entry-edit-link]</div>' ); ?>

						<?php do_atomic( 'close_entry' ); // dschool_close_entry ?>

					</div><!-- .hentry -->

					<?php do_atomic( 'after_entry' ); // dschool_after_entry ?>

					<?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-singular.php template. ?>

					<?php do_atomic( 'after_singular' ); // dschool_after_singular ?>

				<?php endwhile; ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // dschool_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // dschool_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>
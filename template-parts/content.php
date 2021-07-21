<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
		<?php endif; ?>
		
		<?php 
//echo tags berfore title:
$posttags = get_the_tags();
if (is_array($posttags) > 0) {
	foreach($posttags as $tag) {
		$tagbefortitle .= '<span class="tagbefortitle">'.$tag->name.'</span> '; 
		//break, mert csak 1-et iratunk ki
		break;
	}
}
?>

		<?php the_title( sprintf( '<h2 class="entry-title">'.$tagbefortitle.'<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<?php  ?>

	<?php
		//twentysixteen_excerpt(); 
		$excerpt = get_the_excerpt();


		if ( strlen($excerpt) > 0) {
//			twentysixteen_post_thumbnail();
the_post_thumbnail('thumbnail');       // Thumbnail
			$excerpt = preg_replace('/[\r\n]+/', "<br/>\n", $excerpt);
			echo $excerpt;
		} else {
			echo '<span class="fullthumb">';
			twentysixteen_post_thumbnail();
			echo '</span>';
		}

	?>
	
	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */

//			the_excerpt() 

/*

			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
*/
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php // twentysixteen_entry_meta(); ?>
		<?php

			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

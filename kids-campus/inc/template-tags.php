<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Kids Campus
 */

if ( ! function_exists( 'kids_campus_the_attached_image' ) ) : 
/**
 * Prints the attached image with a link to the next attached image.
 */
function kids_campus_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'kids_campus_attachment_size', array( 1200, 1200 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the
	 * URL of the next adjacent image in a gallery, or the first image (if
	 * we're looking at the last image in a gallery), or, in a gallery of one,
	 * just the link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',		
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
	wp_reset_postdata();
}
endif;

/**
 * Returns true if a blog has more than 1 category
 */
function kids_campus_categorized_blog() {
	if ( false === ( $kids_campus_all_the_cool_cats = get_transient( 'kids_campus_all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$kids_campus_all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$kids_campus_all_the_cool_cats = count( $kids_campus_all_the_cool_cats );

		set_transient( 'kids_campus_all_the_cool_cats', $kids_campus_all_the_cool_cats );
	}

	if ( '1' != $kids_campus_all_the_cool_cats ) {
		// This blog has more than 1 category so kids_campus_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so kids_campus_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in kids_campus_categorized_blog
 */
function kids_campus_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'kids_campus_all_the_cool_cats' );
}
add_action( 'edit_category', 'kids_campus_category_transient_flusher' );
add_action( 'save_post',     'kids_campus_category_transient_flusher' );
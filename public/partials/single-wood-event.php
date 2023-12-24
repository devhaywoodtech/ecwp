<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
while ( have_posts() ) :
	the_post();
	do_action( 'modern_single_template_start' );

		echo wp_kses_post( apply_filters( 'modern_single_content_start', '<div class="ecwp_container">' ) );
			apply_filters( 'monthly_events_img', get_the_ID(), 'full' );
			printf( "<div class='ecwp_container_main'>" );
				the_title( '<h1>', '</h1>' );
				the_content();
				do_action( 'modern_organizers', get_the_ID() );
			printf( '</div>' );
		echo wp_kses_post( apply_filters( 'modern_single_content_end', '</div>' ) );

		do_action( 'modern_single_sidebar' );

	do_action( 'modern_single_template_end' );
	endwhile;
get_footer();

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
$queried_object = get_queried_object();
do_action( 'modern_taxonomy_template_title', $queried_object );
printf( '<div id="ecwp-calendar" data-term="%s" data-tax="%s"></div>', esc_attr( $queried_object->term_id ), esc_attr( $queried_object->taxonomy ) );
get_footer();

<?php
/**
 * The admin columns functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/columns
 */

/**
 * The admin columns functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the metabox-specific stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/metabox
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Columns {

	/**
	 * The settings of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      mixed $settings    The settings of this plugin.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Add admin column for the Events
	 *
	 * @param array $columns Default columns.
	 */
	public function add_event_columns( $columns ) {
		$columns = array_slice( $columns, 0, 1, true ) + array( 'image' => 'Image' ) + array_slice( $columns, 1, count( $columns ) - 1, true );
		unset( $columns['date'] );
		unset( $columns['comments'] );
		$new_columns = array(
			'event_start' => __( 'Start Date', 'ecwp' ),
			'event_end'   => __( 'End Date', 'ecwp' ),
			'event_venue' => __( 'Venue', 'ecwp' ),
			'event_color' => __( 'Color', 'ecwp' ),
		);
		return array_merge( $columns, $new_columns );
	}

	/**
	 * Display the custom fields in the Event Column
	 *
	 * @param  array $column Default column.
	 * @param  int   $post_id Id of the post.
	 */
	public function event_column_data( $column, $post_id ) {
		$wp_date_format = $this->settings['date_format'];
		$wp_time_format = $this->settings['time_format'];
		switch ( $column ) {
			case 'image':
				if ( has_post_thumbnail( $post_id ) ) {
					the_post_thumbnail( array( 50, 50 ) );
				}
				break;

			case 'event_start':
				$event_start = get_post_meta( $post_id, 'ecwp_event_date_start', true );
				if ( null !== $event_start && '' !== $event_start ) {
					echo esc_attr( wp_date( $wp_date_format . ' ' . $wp_time_format, $event_start ) );
				}
				break;

			case 'event_end':
				$event_end = get_post_meta( $post_id, 'ecwp_event_date_end', true );
				if ( null !== $event_end && '' !== $event_end ) {
					echo esc_attr( wp_date( $wp_date_format . ' ' . $wp_time_format, $event_end ) );
				}
				break;

			case 'event_venue':
				$venue_id = get_post_meta( $post_id, 'ecwp_venue_id', true );
				if ( null !== $venue_id && '' !== $venue_id ) {
					printf( "<a target='_blank' href='%s'>%s</a>", esc_url( get_edit_post_link( $venue_id ) ), esc_attr( get_the_title( $venue_id ) ) );
				}
				break;

			case 'event_color':
				$event_color = get_post_meta( $post_id, 'ecwp_event_text_bg', true );
				if ( null !== $event_color && '' !== $event_color ) {
					printf( "<div class='ecwp_admin_color' style='background:" . esc_attr( $event_color ) . "'></h3></div>" );
				}
				break;
		}
	}

	/**
	 * Add the custom field as a sortable column.
	 *
	 * @param  array $column Default column.
	 */
	public function sortable_column( $column ) {
		$column['event_start'] = 'event_start';
		$column['event_end']   = 'event_end';
		return $column;
	}

	/**
	 * Modify the query to sort by the custom field.
	 *
	 * @param WP_Query $query \WP_Query.
	 */
	public function event_column_sort( $query ) {
		// Check if it's the admin panel, the wood-event post type, and the desired query.
		if ( ! is_admin() || ! $query->is_main_query() && $query->get( 'post_type' ) !== 'wood-event' ) {
			return;
		}

		$orderby = $query->get( 'orderby' );
		if ( 'event_start' === $orderby || 'event_end' === $orderby ) {
			$sortable_key = 'event_start' === $orderby ? 'ecwp_event_date_start' : 'ecwp_event_date_end';
			$query->set( 'meta_key', $sortable_key );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
}

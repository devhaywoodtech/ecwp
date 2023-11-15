<?php
/**
 * The REST functionlaity used for the Calendar.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/columns
 */

/**
 * The REST functionlaity used for the Calendar.
 *
 * @package    Ecwp
 * @subpackage Ecwp/rest
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Rest {

	/**
	 * The prefix for the control.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $namespace  The rest url.
	 */
	private $namespace;

	/**
	 * The prefix for the control in the event details.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $resource_name  The resource or postype name.
	 */
	private $resource_name;

	/**
	 * Here initialize our namespace and resource name.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->namespace     = 'ecwp/v1';
		$this->resource_name = 'settings';
	}

	/***
	 * Register Custom Fields for the REST API
	 */
	public function register_custom_fields() {
		register_rest_field(
			'wood-event',
			'ecwp',
			array(
				'get_callback'    => array( $this, 'get_ecwp_details' ),
				'update_callback' => null,
				'show_in_rest'    => true,
				'auth_callback'   => true,
				'schema'          => null,
			),
		);
	}


	/**
	 * Retrieve the Custom Fields to the default REST API for events
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $post WordPress REST POST.
	 */
	public function get_ecwp_details( $post ) {

		$post_id             = $post['id'];
		$events              = array(
			'startdate' => '',
			'enddate'   => '',
			'color'     => '#000',
			'excerpt'   => '',
		);
		$events['startdate'] = get_post_meta( $post_id, 'ecwp_event_date_start', true );
		$events['enddate']   = get_post_meta( $post_id, 'ecwp_event_date_end', true );
		$events['color']     = get_post_meta( $post_id, 'ecwp_event_text_bg', true );

		if ( $post['content']['rendered'] ) {
			$events['excerpt'] = substr( wp_strip_all_tags( $post['content']['rendered'] ), 0, 200 ) . '...';
		}

		if ( $post['featured_media'] ) {
			$image = wp_get_attachment_image_src( $post['featured_media'], 'thumbnail' );
			if ( $image ) {
				$events['img'] = $image[0];
			}
		}

		$ecwp_venue_id = get_post_meta( $post_id, 'ecwp_venue_id', true );
		if ( '' !== $ecwp_venue_id ) {
			$events['venue'] = get_the_title( $ecwp_venue_id );
		}

		return $events;
	}

	/**
	 * Add Collection paramater for order by
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $params WordPress REST parameter.
	 */
	public function collect_params( $params ) {
		$params['orderby']['enum'][] = 'ecwp_event_date_start';
		return $params;
	}

	/**
	 * Filter events by custom fields dates
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $args WordPress REST arguments.
	 * @param mixed $request WordPress request.
	 */
	public function filter_by_date( $args, $request ) {

		if ( ! isset( $request['month'] ) ) {
			return $args;
		}

		$year     = sanitize_text_field( $request['year'] );
		$month    = sanitize_text_field( $request['month'] );
		$timezone = sanitize_text_field( $request['timezone'] );

		$start_date = gmdate( $year . '-' . $month . '-01 00:00:00' );
		$end_date   = gmdate( 'Y-m-d 23:59:59', mktime( 0, 0, 0, $month + 1, 0, $year ) );

		$start_timestamp = strtotime( $start_date . ' ' . $timezone );
		$end_timestamp   = strtotime( $end_date . ' ' . $timezone );

		$source_meta_query = array(
			'relation' => 'OR',
			array(
				'relation' => 'AND',
				array(
					'key'     => 'ecwp_event_date_start',
					'value'   => $start_timestamp,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'ecwp_event_date_start',
					'value'   => $end_timestamp,
					'compare' => '<=',
					'type'    => 'NUMERIC',
				),
			),
			array(
				'relation' => 'AND',
				array(
					'key'     => 'ecwp_event_date_end',
					'value'   => $start_timestamp,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'ecwp_event_date_end',
					'value'   => $end_timestamp,
					'compare' => '<=',
					'type'    => 'NUMERIC',
				),
			),
		);

		$args['meta_query'][] = $source_meta_query;
		$args['meta_key']     = 'ecwp_event_date_start'; //phpcs:ignore
		$args['orderby']      = 'meta_value_num';
		$args['order']        = 'asc';
		return $args;
	}

	/**
	 * Filter events by upcoming date
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $args WordPress REST arguments.
	 * @param mixed $request WordPress request.
	 */
	public function filter_by_upcoming( $args, $request ) {
		if ( ! isset( $request['upcoming'] ) ) {
			return $args;
		}
		$year     = sanitize_text_field( $request['currentYear'] );
		$month    = sanitize_text_field( $request['currentMonth'] );
		$date     = sanitize_text_field( $request['currentDate'] );
		$timezone = sanitize_text_field( $request['timezone'] );

		$start_date      = gmdate( $year . '-' . $month . '-' . $date . ' 00:00:00' );
		$start_timestamp = strtotime( $start_date . ' ' . $timezone );

		$source_meta_query = array(
			'relation' => 'AND',
			array(
				'key'     => 'ecwp_event_date_start',
				'value'   => $start_timestamp,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			),
		);

		$args['meta_query'][] = $source_meta_query;
		$args['meta_key']     = 'ecwp_event_date_start'; //phpcs:ignore
		$args['orderby']      = 'meta_value_num';
		$args['order']        = 'asc';
		return $args;
	}
	/**
	 * Register our routes.
	 *
	 * @since    1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				// Here we register the readable endpoint for collections.
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/savesettings',
			array(
				// Here we register the readable endpoint for collections.
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'save_settings' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Check permissions for the posts.
	 *
	 * @param WP_REST_Request $request Current request.
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! isset( $request ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}
		return true;
	}

	/**
	 * Grabs the Settings .
	 */
	public function get_items() {
		$settings = get_option( ECWP_SETTINGS );
		$pages    = get_pages();
		$data     = array();
		if ( empty( $settings ) && empty( $pages ) ) {
			return rest_ensure_response( $data );
		}
		$data['settings'] = $settings;
		$data['pages']    = $pages;
		// Return all of our response data.
		return rest_ensure_response( $data );
	}

	/**
	 * Save the Settings.
	 *
	 * @param mixed WP_REST_Request $request WP REST REQUEST.
	 */
	public function save_settings( WP_REST_Request $request ) {
		$ecwp_settings = $request->get_param( 'ecwp_settings' );
		update_option( ECWP_SETTINGS, $ecwp_settings );
		return rest_ensure_response( $ecwp_settings );
	}

	/**
	 * Sets up the proper HTTP status code for authorization.
	 */
	public function authorization_status_code() {
		$status = 401;
		if ( is_user_logged_in() ) {
			$status = 403;
		}
		return $status;
	}
}

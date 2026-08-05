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
	 * Resolve a request timezone string to a DateTimeZone, falling back to the site timezone.
	 *
	 * @param string $timezone Timezone identifier from the request.
	 * @return DateTimeZone
	 */
	private function get_timezone( $timezone ) {
		if ( $timezone && in_array( $timezone, timezone_identifiers_list(), true ) ) {
			return new DateTimeZone( $timezone );
		}
		return wp_timezone();
	}

	/**
	 * Filter events by the requested month.
	 */
	public function filter_by_date( $args, $request ) {
		if ( ! isset( $request['month'] ) ) {
			return $args;
		}

		$year     = absint( $request['year'] );
		$month    = absint( $request['month'] );
		$timezone = $this->get_timezone( sanitize_text_field( $request['timezone'] ) );

		// First and last moment of the requested month, in the visitor's timezone.
		$start = new DateTime( sprintf( '%04d-%02d-01 00:00:00', $year, $month ), $timezone );
		$end   = clone $start;
		$end->modify( 'last day of this month' );
		$end->setTime( 23, 59, 59 );

		$start_timestamp = $start->getTimestamp();
		$end_timestamp   = $end->getTimestamp();

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
	 * Filter events by upcoming date.
	 */
	public function filter_by_upcoming( $args, $request ) {
		if ( ! isset( $request['upcoming'] ) ) {
			return $args;
		}

		$year     = absint( $request['currentYear'] );
		$month    = absint( $request['currentMonth'] );
		$date     = absint( $request['currentDate'] );
		$timezone = $this->get_timezone( sanitize_text_field( $request['timezone'] ) );

		$start           = new DateTime( sprintf( '%04d-%02d-%02d 00:00:00', $year, $month, $date ), $timezone );
		$start_timestamp = $start->getTimestamp();

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
	
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_items' ),
					// Display settings are needed to render the public calendar, so reads stay public.
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/savesettings',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'save_settings' ),
					// Writing settings must be restricted to administrators.
					'permission_callback' => array( $this, 'save_settings_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Only administrators may write the plugin settings.
	 *
	 * @param WP_REST_Request $request Current request.
	 * @return true|WP_Error
	 */
	public function save_settings_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'You are not allowed to update these settings.', 'ecwp' ),
				array( 'status' => $this->authorization_status_code() )
			);
		}
		return true;
	}

	/**
	 * Grabs the Settings.
	 */
	public function get_items() {
		$settings = get_option( ECWP_SETTINGS );
		$data     = array(
			'settings' => ! empty( $settings ) ? $settings : array(),
			'pages'    => array(),
		);
		// The page list only feeds the admin settings dropdown; do not expose it to anonymous users.
		if ( current_user_can( 'edit_pages' ) ) {
			$data['pages'] = get_pages();
		}
		return rest_ensure_response( $data );
	}

	/**
	 * Save the Settings.
	 *
	 * @param WP_REST_Request $request WP REST request.
	 */
	public function save_settings( WP_REST_Request $request ) {
		// Defense in depth: re-check capability even though the permission callback already did.
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'You are not allowed to update these settings.', 'ecwp' ),
				array( 'status' => $this->authorization_status_code() )
			);
		}
		$clean = $this->sanitize_settings( $request->get_param( 'ecwp_settings' ) );
		update_option( ECWP_SETTINGS, $clean );
		return rest_ensure_response( $clean );
	}

	/**
	 * Recursively sanitize the settings payload before persisting it.
	 *
	 * @param mixed $value Raw setting value.
	 * @return mixed Sanitized value.
	 */
	private function sanitize_settings( $value ) {
		if ( is_array( $value ) ) {
			$clean = array();
			foreach ( $value as $key => $item ) {
				$clean[ sanitize_key( $key ) ] = $this->sanitize_settings( $item );
			}
			return $clean;
		}
		return sanitize_text_field( $value );
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

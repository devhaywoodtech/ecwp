<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/metabox
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the metabox-specific stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/metabox
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Metabox {


	/**
	 * The prefix for the control.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name  The prefix name for the all control .
	 */
	private $slug;

	/**
	 * The prefix for the control in the event details.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name  The prefix name for the event control .
	 */
	private $event;

	/**
	 * The prefix for the control in the location details.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name  The prefix name for the location control .
	 */
	private $location;

	/**
	 * The prefix for the control in the organizer details.
	 * Do not change this.
	 * It may not return the expected custom fields which are already saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name  The prefix name for the organizer control .
	 */
	private $organizer;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->slug      = 'ecwp_';
		$this->event     = $this->slug . 'event_';
		$this->location  = $this->slug . 'location_';
		$this->organizer = $this->slug . 'organizer_';
	}

	/***
	 * Add Metabox class for this plugin
	 *
	 * @param      array $classes Default classes.
	 */
	public function add_metabox_classes( $classes ) {
		array_push( $classes, 'ecwp_metabox' );
		return $classes;
	}

	/**
	 * Set up and add the meta box.
	 */
	public function add() {
		add_meta_box(
			'ecwp_details',
			__( 'Event Details', 'ecwp' ),
			array( $this, 'event_details' ),
			array( 'wood-event' ),
			'normal',
			'high'
		);
		add_meta_box(
			'ecwp_locaton_details',
			__( 'Location Details', 'ecwp' ),
			array( $this, 'location_details' ),
			array( 'wood-event', 'wood-venue' ),
			'normal',
			'high'
		);
		add_meta_box(
			'organizer_details_for_event',
			__( 'Organizer Details', 'ecwp' ),
			array( $this, 'organizer_details_for_event' ),
			array( 'wood-event' ),
			'normal',
			'high'
		);
		add_meta_box(
			'ecwp_organizer_details',
			__( 'Organizer Details', 'ecwp' ),
			array( $this, 'organizer_details' ),
			array( 'wood-organizers' ),
			'normal',
			'high'
		);
	}


	/**
	 * Save the meta box selections.
	 *
	 * @param int     $post_id  The post ID.
	 * @param WP_Post $post  Post object.
	 */
	public function save( int $post_id, $post ) {

		// bail out if this is an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// bail out if this is not an event item.
		if ( 'wood-event' !== $post->post_type && 'wood-venue' !== $post->post_type && 'wood-organizers' !== $post->post_type ) {
			return;
		}

		// Check the Postype Event.
		if ( 'wood-event' === $post->post_type && isset( $_POST[ $this->event . 'nonce' ] ) && wp_verify_nonce( sanitize_key( $_POST[ $this->event . 'nonce' ] ), $this->event . 'action' ) ) {

			$original_venue_id = ! empty( $_POST[ $this->slug . 'venue_id' ] ) ? absint( $_POST[ $this->slug . 'venue_id' ] ) : '';
			$venue_id          = ! empty( $_POST[ $this->slug . 'venue_id' ] ) ? absint( $_POST[ $this->slug . 'venue_id' ] ) : '';

			if ( empty( $venue_id ) ) {
				// If empty venue title do not save venue meta data.
				$venue_title = ! empty( $_POST[ $this->location . 'text_title' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->location . 'text_title' ] ) ) : '';
				if ( '' !== $venue_title ) {
					$venue    = new Ecwp_Posttypes( 'wood-venue', $venue_title );
					$venue_id = $venue->save();
					// Save Venue for Event.
					if ( ! empty( $venue_id ) && absint( $venue_id ) ) {
						update_post_meta( $post_id, $this->slug . 'venue_id', absint( $venue_id ) );
					}
				}
			} else {
				update_post_meta( $post_id, $this->slug . 'venue_id', absint( $venue_id ) );
			}

			$organizer_ids = ! empty( $_POST[ $this->slug . 'organizer_id' ] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST[ $this->slug . 'organizer_id' ] ) ) : array();

			$organizer_ids = array_values( $organizer_ids );

			// If empty organizer title do not save organizer meta data.
			if ( ! empty( $_POST[ $this->organizer . 'text_title' ] ) && is_array( $_POST[ $this->organizer . 'text_title' ] ) ) {
				foreach ( $_POST[ $this->organizer . 'text_title' ] as $key => $organizer_arr ) { //phpcs:ignore
					$organizer_title = ! empty( $organizer_arr ) ? sanitize_text_field( wp_unslash( $organizer_arr ) ) : '';
					if ( '' !== $organizer_title ) {
						$organizer       = new Ecwp_Posttypes( 'wood-organizers', $organizer_title );
						$organizer_id    = $organizer->save();
						$organizer_ids[] = $organizer_id;

						// Save meta values for wood-organizers post type using $organizer_id.
						if ( ! empty( $organizer_id ) && absint( $organizer_id ) ) {
							if ( ! empty( $_POST[ $this->organizer . 'text_phone' ][ $key ] ) ) {
								$newvalue = sanitize_text_field( wp_unslash( $_POST[ $this->organizer . 'text_phone' ][ $key ] ) );
								update_post_meta( $organizer_id, $this->organizer . 'text_phone', $newvalue );
							}
							if ( ! empty( $_POST[ $this->organizer . 'url_website' ][ $key ] ) ) {
								$newvalue = esc_url_raw( wp_unslash( $_POST[ $this->organizer . 'url_website' ][ $key ] ) );
								update_post_meta( $organizer_id, $this->organizer . 'url_website', $newvalue );
							}
							if ( ! empty( $_POST[ $this->organizer . 'email_email' ][ $key ] ) ) {
								$newvalue = sanitize_email( wp_unslash( $_POST[ $this->organizer . 'email_email' ][ $key ] ) );
								update_post_meta( $organizer_id, $this->organizer . 'email_email', $newvalue );
							}
						}
					}
				}
			}
			// Save Organizer Array for Event.
			update_post_meta( $post_id, $this->slug . 'organizer_id', array_unique( array_filter( $organizer_ids ) ) );
		} else {
			$original_venue_id = '';
		}

		// Check the Postype venue.
		if ( 'wood-venue' === $post->post_type ) {
			$venue_id = $post_id;
		}

		if ( 'wood-organizers' === $post->post_type ) {
			$organizer_original_id = $post_id;
		}

		$ecwp_inputs  = new Ecwp_Inputs();
		$event_inputs = $ecwp_inputs->event_inputs( $this->event );
		$venue_inputs = $ecwp_inputs->venue_inputs( $this->location );
		// Save meta values for wood-event post type.
		foreach ( $event_inputs as $key ) {
			if ( $key !== $this->event . 'nonce' ) {
				if ( str_contains( $key, $this->event . 'text_' ) || str_contains( $key, $this->event . 'date_' ) ) {
					$newvalue = ! empty( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
				}
				if ( str_contains( $key, $this->event . 'url_' ) ) {
					$newvalue = ! empty( $_POST[ $key ] ) ? esc_url_raw( wp_unslash( $_POST[ $key ] ) ) : '';
				}
				update_post_meta( $post_id, $key, $newvalue );
			}
		}

		// Save meta values for wood-venue post type using $venue_id.
		foreach ( $venue_inputs as $key ) {
			if ( $key !== $this->event . 'nonce' ) {
				if ( ! empty( $venue_id ) && absint( $venue_id ) && str_contains( $key, $this->location ) && $original_venue_id !== $venue_id ) {
					if ( str_contains( $key, $this->location . 'text_' ) ) {
						$newvalue = ! empty( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
					}
					if ( str_contains( $key, $this->location . 'url_' ) ) {
						$newvalue = ! empty( $_POST[ $key ] ) ? esc_url_raw( wp_unslash( $_POST[ $key ] ) ) : '';
					}
					update_post_meta( $venue_id, $key, $newvalue );
				}
			}
		}
	}

	/**
	 * Save the meta box selections for Organizer.
	 *
	 * @param int     $post_id  The post ID.
	 * @param WP_Post $post  Post object.
	 */
	public function save_organizer( int $post_id, $post ) {

		// bail out if this is an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// bail out if this is not an organizers item.
		if ( 'wood-organizers' !== $post->post_type ) {
			return;
		}

		// Check the Postype Organizers.
		if ( 'wood-organizers' === $post->post_type && isset( $_POST[ $this->organizer . 'nonce' ] ) && wp_verify_nonce( sanitize_key( $_POST[ $this->organizer . 'nonce' ] ), $this->organizer . 'action' ) ) {
			$ecwp_inputs      = new Ecwp_Inputs();
			$organizer_inputs = $ecwp_inputs->organizer_inputs( $this->organizer );

			foreach ( $organizer_inputs as $input ) {
				if ( $input !== $this->organizer . 'nonce' ) {
					if ( ! empty( $post_id ) && absint( $post_id ) && str_contains( $input, $this->organizer ) ) {
						if ( str_contains( $input, $this->organizer . 'text_' ) ) {
							$newvalue = ! empty( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';
						}
						if ( str_contains( $input, $this->organizer . 'url_' ) ) {
							$newvalue = ! empty( $_POST[ $input ] ) ? esc_url_raw( wp_unslash( $_POST[ $input ] ) ) : '';
						}
						if ( str_contains( $input, $this->organizer . 'email_' ) ) {
							$newvalue = ! empty( $_POST[ $input ] ) ? sanitize_email( wp_unslash( $_POST[ $input ] ) ) : '';
						}
						update_post_meta( $post_id, $input, $newvalue );
					}
				}
			}
		}
	}

	/**
	 * Display the meta box HTML for the event details.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public function event_details( $post ) {
		$values = get_post_meta( $post->ID );
		wp_nonce_field( $this->event . 'action', $this->event . 'nonce' );

		// Date & Time Controls Loop.
		printf( "<div class='ecwp_field_heading'><h3>" . esc_html__( 'Date & Time', 'ecwp' ) . '</h3></div>' );
		printf( "<div class='ecwp_inside'>" );
		$ecwp_date_range = is_array( $values ) && array_key_exists( $this->event . 'date_range', $values ) ? $values[ $this->event . 'date_range' ][0] : '';
		$ecwp_date_start = is_array( $values ) && array_key_exists( $this->event . 'date_start', $values ) ? $values[ $this->event . 'date_start' ][0] : '';
		$ecwp_date_end   = is_array( $values ) && array_key_exists( $this->event . 'date_end', $values ) ? $values[ $this->event . 'date_end' ][0] : '';
		$controls        = array(
			$this->event . 'date_range' => array( 'date', esc_html__( 'Pick your Event Date Range', 'ecwp' ) . '(' . ECWP_JS_ADMIN_DATE . ')', 'datetimes', $ecwp_date_range ),
			$this->event . 'date_start' => array( 'hidden', esc_html__( 'Event Start Date', 'ecwp' ), 'startdate', $ecwp_date_start ),
			$this->event . 'date_end'   => array( 'hidden', esc_html__( 'Event End Date', 'ecwp' ), 'enddate', $ecwp_date_end ),
		);
		foreach ( $controls as $keys => $control ) {
			new Ecwp_Form( $keys, $control[0], $control[1], $control[2], $control[3], array() );
		}
		printf( '</div>' );

		// Other Details Control Loop.
		printf( "<div class='ecwp_field_heading'><h3>" . esc_html__( 'Event Website', 'ecwp' ) . '</h3></div>' );
		printf( "<div class='ecwp_inside'>" );
		$ecwp_url_website = is_array( $values ) && array_key_exists( $this->event . 'url_website', $values ) ? $values[ $this->event . 'url_website' ][0] : '';
		$ecwp_color_bg    = is_array( $values ) && array_key_exists( $this->event . 'text_bg', $values ) ? $values[ $this->event . 'text_bg' ][0] : '';
		$controls         = array(
			$this->event . 'url_website' => array( 'url', esc_html__( 'URL', 'ecwp' ), 'website ecwp_fullwidth', $ecwp_url_website ),
			$this->event . 'text_bg'     => array( 'text', esc_html__( 'Highlight Color', 'ecwp' ), 'ecwp_colors ecwp_fullwidth', $ecwp_color_bg ),
		);
		foreach ( $controls as $keys => $control ) {
			new Ecwp_Form( $keys, $control[0], $control[1], $control[2], $control[3], array() );
		}
		printf( '</div>' );
	}

	/**
	 * Display the meta box HTML for the location details.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public function location_details( $post ) {
		printf( "<div class='ecwp_field_heading'><h3>" . esc_html__( 'Venue', 'ecwp' ) . '</h3></div>' );
		$venue_id = get_post_meta( $post->ID, $this->slug . 'venue_id', true );

		if ( '' === $venue_id ) {
			printf( "<div class='ecwp_inside'>" );
			$values     = get_post_meta( $post->ID );
			$address    = is_array( $values ) && array_key_exists( $this->location . 'text_address', $values ) ? $values[ $this->location . 'text_address' ][0] : '';
			$city       = is_array( $values ) && array_key_exists( $this->location . 'text_city', $values ) ? $values[ $this->location . 'text_city' ][0] : '';
			$state      = is_array( $values ) && array_key_exists( $this->location . 'text_state', $values ) ? $values[ $this->location . 'text_state' ][0] : '';
			$country    = is_array( $values ) && array_key_exists( $this->location . 'text_country', $values ) ? $values[ $this->location . 'text_country' ][0] : '';
			$postalcode = is_array( $values ) && array_key_exists( $this->location . 'text_postalcode', $values ) ? $values[ $this->location . 'text_postalcode' ][0] : '';
			$phone      = is_array( $values ) && array_key_exists( $this->location . 'text_phone', $values ) ? $values[ $this->location . 'text_phone' ][0] : '';
			$website    = is_array( $values ) && array_key_exists( $this->location . 'url_website', $values ) ? $values[ $this->location . 'url_website' ][0] : '';

			// Add Venue name if this metabox is listed in the wood-event postype.
			if ( 'wood-event' === $post->post_type ) {
				$title = is_array( $values ) && array_key_exists( $this->location . 'text_title', $values ) ? $values[ $this->location . 'text_title' ][0] : '';
				new Ecwp_Form( $this->location . 'text_title', 'text', esc_html__( 'Venue Name', 'ecwp' ), 'ecwp_fullwidth', $title, array() );
			}

			$controls = array(
				$this->location . 'text_address'    => array( 'text', esc_html__( 'Address', 'ecwp' ), 'ecwp_fullwidth', $address ),
				$this->location . 'text_city'       => array( 'text', esc_html__( 'Add City', 'ecwp' ), '', $city ),
				$this->location . 'text_state'      => array( 'text', esc_html__( 'State or Province', 'ecwp' ), '', $state ),
				$this->location . 'text_country'    => array( 'country', esc_html__( 'Country', 'ecwp' ), '', $country ),
				$this->location . 'text_postalcode' => array( 'text', esc_html__( 'Postal Code', 'ecwp' ), '', $postalcode ),
				$this->location . 'text_phone'      => array( 'text', esc_html__( 'Phone', 'ecwp' ), '', $phone ),
				$this->location . 'url_website'     => array( 'text', esc_html__( 'Website', 'ecwp' ), '', $website ),
			);
			foreach ( $controls as $keys => $control ) {
				new Ecwp_Form( $keys, $control[0], $control[1], $control[2], $control[3], array() );
			}
			printf( '</div>' );

			// Select Already existing Venue Names from wood-event postypes.
			if ( 'wood-event' === $post->post_type ) {
				new Ecwp_Form( $this->slug . 'venue_id', 'wpdropdown', esc_html__( 'Select Existing Venue Name', 'ecwp' ), 'ecwp_select', $venue_id, array( 'wood-venue' ) );
			}
		} else {
			new Ecwp_Form( $this->slug . 'venue_id', 'wpdropdown', esc_html__( 'Select Existing Venue Name', 'ecwp' ), 'ecwp_select', $venue_id, array( 'wood-venue' ) );
			printf( "<a href='%s' target='_blank'>%s</a>", esc_url( get_edit_post_link( $venue_id ) ), esc_html__( 'Edit Venue', 'ecwp' ) );
		}
	}

	/**
	 * Display the meta box HTML for the Organizer details for Event.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public function organizer_details_for_event( $post ) {
		printf( "<div class='ecwp_field_heading'><h3>" . esc_html__( 'Organizers', 'ecwp' ) . '</h3></div>' );
		$organizer_id = get_post_meta( $post->ID, $this->slug . 'organizer_id', true );
		if ( ! empty( $organizer_id ) ) {
			printf( "<div class='ecwp_inside ecwp_organizer_inside'>" );
			foreach ( $organizer_id as $id ) {
				printf( "<div class='ecwp_inside ecwp_organizer_select' id='ecwp_org_select_%d'>", esc_attr( $id ) );
					new Ecwp_Form( $this->slug . 'organizer_id', 'wpdropdown', esc_html__( 'Select Existing Organizer Name', 'ecwp' ), '', $id, array( 'wood-organizers' ), true );

					printf( "<div class='ecwp_organizer_actions'>" );
						printf( "<a href='%s' class='ecwp_edit_org' target='_blank'>%s</a>", esc_url( get_edit_post_link( $id ) ), esc_html__( 'Edit Organizer', 'ecwp' ) );
						printf( "<a href='%s' data-id='%d' class='ecwp_remove_org'>%s</a>", 'javascript:', esc_attr( $id ), esc_html__( 'Remove Organizer', 'ecwp' ) );
					printf( '</div>' );
				printf( '</div>' );
			}
			printf( '</div>' );

			printf( "<div class='ecwp_organizer_content'>" );
			printf( "<div class='ecwp_new ecwp_organizer_new'><a href='javascript:'>%s</a></div>", esc_html__( 'Create or Add New Organizer', 'ecwp' ) );
			printf( '</div>' );

		} else {
			printf( "<div class='ecwp_inside ecwp_organizer_inside'>" );
			printf( '</div>' );
			printf( "<div class='ecwp_organizer_content'>" );
			printf( "<div class='ecwp_new ecwp_organizer_new'><a href='javascript:'>%s</a></div>", esc_html__( 'Create or Add New Organizer', 'ecwp' ) );
			printf( '</div>' );
		}
	}

	/**
	 * Display the meta box HTML for the Organizer details for Organizers Post type.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public function organizer_details( $post ) {
		wp_nonce_field( $this->organizer . 'action', $this->organizer . 'nonce' );
		printf( "<div class='ecwp_field_heading'><h3>" . esc_html__( 'Organizers', 'ecwp' ) . '</h3></div>' );
		printf( "<div class='ecwp_inside ecwp_organizer_inside'>" );
		$this->org_fields( $post->ID, 'wood-organizers', false );
		printf( '</div>' );
	}

	/**
	 * Display the meta box HTML for the Organizer details via AJAX.
	 *
	 * @param integer $post_id Event ID.
	 * @param string  $post_type Name of the Post type.
	 * @param string  $ajax Check it is from AJAX or not.
	 */
	public function org_fields( $post_id = null, $post_type = null, $ajax = false ) {
		$count = 0;
		if ( isset( $_REQUEST['action'] ) && 'org_fields' === $_REQUEST['action'] ) {
			$ajax = true;
			if ( $ajax ) {
				check_ajax_referer( 'handle_org', 'security' );
			}
			$post_id   = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : 0;
			$post_type = isset( $_REQUEST['posttype'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['posttype'] ) ) : 'wood-event';
			$count     = isset( $_REQUEST['count'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['count'] ) ) : 0;
		}
		$organizers       = wp_count_posts( 'wood-organizers' );
		$organizers_count = 0;
		if ( $organizers ) {
			$organizers_count = $organizers->publish;
		}

		$values  = get_post_meta( $post_id );
		$phone   = is_array( $values ) && array_key_exists( $this->organizer . 'text_phone', $values ) ? $values[ $this->organizer . 'text_phone' ][0] : '';
		$website = is_array( $values ) && array_key_exists( $this->organizer . 'url_website', $values ) ? $values[ $this->organizer . 'url_website' ][0] : '';
		$email   = is_array( $values ) && array_key_exists( $this->organizer . 'email_email', $values ) ? $values[ $this->organizer . 'email_email' ][0] : '';

		printf( "<div class='ecwp_inside ecwp_organizer_select_inputs' id='ecwp_org_select_%d' data-id='%d'>", esc_attr( $count ), esc_attr( $count ) );
		// Add Organizer name if this metabox is listed in the wood-event postype.
		if ( 'wood-event' === $post_type ) {
			if ( $organizers_count > 0 ) {
				new Ecwp_Form( $this->slug . 'organizer_id', 'wpdropdown', esc_html__( 'Select Existing Organizer Name', 'ecwp' ), 'ecwp_select', '', array( 'wood-organizers' ), $ajax );

			}
			if ( $organizers_count > 0 ) {
				printf( "<div class='ecwp_other_fields' id='ecwp_org_other_%d'>", esc_attr( $count ) );
				printf( "<div class='ecwp_new_or'>%s</div>", esc_html__( 'OR', 'ecwp' ) );
			}
			$title = is_array( $values ) && array_key_exists( $this->organizer . 'text_title', $values ) ? $values[ $this->organizer . 'text_title' ][0] : '';
			new Ecwp_Form( $this->organizer . 'text_title', 'text', esc_html__( 'Organizer Name', 'ecwp' ), 'ecwp_fullwidth', $title, array(), $ajax );
		}

		$controls = array(
			$this->organizer . 'text_phone'  => array( 'text', esc_html__( 'Phone', 'ecwp' ), 'ecwp_fullwidth', $phone, $ajax ),
			$this->organizer . 'url_website' => array( 'url', esc_html__( 'Website', 'ecwp' ), 'ecwp_fullwidth', $website, $ajax ),
			$this->organizer . 'email_email' => array( 'email', esc_html__( 'Email', 'ecwp' ), 'ecwp_fullwidth', $email, $ajax ),
		);
		foreach ( $controls as $keys => $control ) {
			new Ecwp_Form( $keys, $control[0], $control[1], $control[2], $control[3], array(), $control[4] );
		}
		if ( $organizers_count > 0 && 'wood-event' === $post_type ) {
			printf( '</div>' ); // Close the .ecwp_other_fields div.
		}
		if ( 'wood-event' === $post_type ) {
			printf( "<a href='%s' data-id='%d' class='ecwp_remove_org'>%s</a>", 'javascript:', esc_attr( $count ), esc_html__( 'Remove Organizer', 'ecwp' ) );
		}
		printf( '</div>' );
		if ( $ajax ) {
			wp_die();
		}
	}
}

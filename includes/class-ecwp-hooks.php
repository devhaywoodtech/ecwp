<?php
/**
 * Define the hooks functionality
 *
 * Loads and defines the hooks files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/includes
 */

/**
 * Define the hooks functionality.
 *
 * Loads and defines the hooks files for this plugin
 *
 * @since      1.0.0
 * @package    Ecwp
 * @subpackage Ecwp/includes
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Hooks {


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
		$this->slug      = 'ecwp_';
		$this->event     = $this->slug . 'event_';
		$this->location  = $this->slug . 'location_';
		$this->organizer = $this->slug . 'organizer_';
		$this->settings  = $settings;
	}

	/**
	 * Hooks for the Start of the Event Single Template.
	 *
	 * @return html
	 **/
	public function template_start() {
		return printf( "<div class='ecwp_monthly_events'>" );
	}

	/**
	 * Hooks for the End of the Event Single Template.
	 *
	 * @return html
	 **/
	public function template_end() {
		return printf( '</div>' );
	}

	/**
	 * Display Event organizers.
	 *
	 * @param integer $post_id Event ID.
	 */
	public function modern_organizers( $post_id ) {
		$organizers = $this->get_organizers( $post_id );
		if ( ! empty( $organizers ) ) {
			if ( $organizers->have_posts() ) {
				printf( '<div class="ecwp_organizers">' );
				while ( $organizers->have_posts() ) {
					$organizers->the_post();
					printf( '<div class="ecwp_org">' );
					apply_filters( 'monthly_events_img', get_the_ID(), 'thumbnail' );
					printf( '<div class="ecwp_org_content">' );
						the_title( '<p class="ecwp_org_title">', '</p>' );
						do_action( 'modern_organizers_details', get_the_ID(), 'text_phone' );
						do_action( 'modern_organizers_details', get_the_ID(), 'email_email' );
						do_action( 'modern_organizers_details', get_the_ID(), 'url_website' );
					printf( '</div>' );
					printf( '</div>' );
				}
				wp_reset_postdata();
				printf( '</div>' );
			}
		}
	}

	/**
	 * Get Organizer Query and Return result.
	 *
	 * @param integer $post_id Event ID.
	 */
	public function get_organizers( $post_id ) {
		$organizers = get_post_meta( $post_id, $this->slug . 'organizer_id', true );
		if ( ! empty( $organizers ) ) {
			$query = new WP_Query(
				array(
					'post_type' => 'wood-organizers',
					'post__in'  => $organizers,
				)
			);
			return $query;
		}
		return array();
	}

	/**
	 * Hooks for the displaying the Organizers Meta.
	 *
	 * @param integer $post_id Event ID.
	 * @param string  $meta_key Organizer Custom meta key.
	 **/
	public function modern_organizers_details( $post_id, $meta_key ) {
		$meta_value = get_post_meta( $post_id, $this->organizer . $meta_key, true );
		if ( '' !== $meta_value && null !== $meta_value ) {
			switch ( $meta_key ) {
				case 'text_phone':
					printf( '<div class="ecwp_items"><span class="material-icons">phone</span><div class="ecwp_item"><div class="ecwp_title"><a href="tel:%s">%s</a></div></div></div>', esc_attr( str_replace( ' ', '', $meta_value ) ), esc_attr( $meta_value ) );
					break;
				case 'email_email':
					printf( '<div class="ecwp_items"><span class="material-icons">email</span><div class="ecwp_item"><div class="ecwp_title"><a href="mailto:%s">%s</a></div></div></div>', esc_attr( $meta_value ), esc_attr( $meta_value ) );
					break;
				case 'url_website':
					printf( '<div class="ecwp_items"><span class="material-icons">public</span><div class="ecwp_item"><div class="ecwp_title"><a target="_blank" href="%s">%s</a></div></div></div>', esc_attr( $meta_value ), esc_html__( 'Link to website', 'ecwp' ) );
					break;
			}
		}
	}

	/**
	 * Hooks for the Sidebar Content.
	 **/
	public function modern_sidebar() {
		echo wp_kses_post( apply_filters( 'modern_single_sidebar_start', '<div class="ecwp_sidebar">' ) );
		echo wp_kses_post( apply_filters( 'modern_sidebar_title', sprintf( "<div class='ecwp_sidebar_title'>%s</div>", __( 'Event Details', 'ecwp' ) ) ) );
		do_action( 'monthly_events_start_date', get_the_ID() );
		do_action( 'monthly_events_end_date', get_the_ID() );
		do_action( 'monthly_events_location', get_the_ID() );
		do_action( 'monthly_events_address', get_the_ID() );
		do_action( 'monthly_events_category', get_the_ID(), 'wood-category', __( 'Category', 'ecwp' ) );
		do_action( 'monthly_events_category', get_the_ID(), 'wood-event-tag', __( 'Tags', 'ecwp' ) );
		do_action( 'monthly_events_website', get_the_ID() );
		do_action( 'monthly_events_button', get_the_ID() );
		echo wp_kses_post( apply_filters( 'modern_single_sidebar_end', '</div>' ) );
	}

	/**
	 * Hooks for the displaying the Event Start Date & Time.
	 *
	 * @return time
	 *
	 * @param integer $post_id Event ID.
	 **/
	public function return_start_date( $post_id ) {
		$startdate = get_post_meta( $post_id, $this->event . 'date_start', true );
		$start     = apply_filters( 'monthly_events_time', $startdate, $post_id );
		return printf( '<div class="ecwp_items"><span class="material-icons">event_available</span><div class="ecwp_item"><div class="ecwp_title">%s</div><time id="ecwp_startDate" datetime="%s" unix=%s itemprop="startDate">&nbsp;</time></div></div>', esc_html__( 'Start date', 'ecwp' ), esc_attr( $start ), esc_attr( $startdate ) );
	}

	/**
	 * Hooks for the displaying the Event End Date & Time.
	 *
	 * @return time
	 *
	 * @param integer $post_id Event ID.
	 **/
	public function return_end_date( $post_id ) {
		$enddate = get_post_meta( $post_id, $this->event . 'date_end', true );
		$end     = apply_filters( 'monthly_events_time', $enddate, $post_id );
		return printf( '<div class="ecwp_items"><span class="material-icons">event_busy</span><div class="ecwp_item"><div class="ecwp_title">%s</div><time id="ecwp_endDate" datetime="%s" unix=%s itemprop="endDate">&nbsp;</time></div></div>', esc_html__( 'End date', 'ecwp' ), esc_attr( $end ), esc_attr( $enddate ) );
	}

	/**
	 * Hooks for the displaying the Event Location.
	 *
	 * @return location
	 *
	 * @param integer $post_id Event ID.
	 **/
	public function return_location( $post_id ) {
		$location = get_post_meta( $post_id, $this->slug . 'venue_id', true );
		if ( '' !== $location && null !== $location ) {
			$venue = apply_filters( 'monthly_events_venue', $location );
			if ( '' !== $venue && null !== $venue ) {
				return printf( '<div class="ecwp_items"><span class="material-icons">location_on</span><div class="ecwp_item"><div class="ecwp_title">%s</div><p>%s</p></div></div>', esc_html__( 'Location', 'ecwp' ), esc_attr( $venue ) );
			}
		}
	}

	/**
	 * Hooks for the displaying the Event Address.
	 *
	 * @return location
	 *
	 * @param integer $post_id Event ID.
	 * @param bool    $return_format Return Address parts if its true.
	 **/
	public function return_address( $post_id, $return_format = false ) {
		$location_id    = get_post_meta( $post_id, $this->slug . 'venue_id', true );
		$meta           = get_post_meta( $location_id );
		$address_data   = array(
			'street'     => isset( $meta[ $this->location . 'text_address' ][0] ) ? $meta[ $this->location . 'text_address' ][0] : '',
			'city'       => isset( $meta[ $this->location . 'text_city' ][0] ) ? $meta[ $this->location . 'text_city' ][0] : '',
			'state'      => isset( $meta[ $this->location . 'text_state' ][0] ) ? $meta[ $this->location . 'text_state' ][0] : '',
			'country'    => isset( $meta[ $this->location . 'text_country' ][0] ) ? $meta[ $this->location . 'text_country' ][0] : '',
			'postalCode' => isset( $meta[ $this->location . 'text_postalcode' ][0] ) ? $meta[ $this->location . 'text_postalcode' ][0] : '',
		);
		$address_parts  = array_filter(
			array(
				$meta[ $this->location . 'text_address' ][0] ?? '',
				$meta[ $this->location . 'text_city' ][0] ?? '',
				$meta[ $this->location . 'text_state' ][0] ?? '',
				$meta[ $this->location . 'text_country' ][0] ?? '',
				$meta[ $this->location . 'text_postalcode' ][0] ?? '',
			)
		);
		$address_string = implode( ', ', $address_parts );
		if ( $return_format ) {
			return $address_string;
		}
		if ( '' !== $address_string ) {
			return printf( '<div class="ecwp_items"><span class="material-icons">my_location</span><div class="ecwp_item"><div class="ecwp_title">%s</div><p>%s</p></div></div>', esc_html__( 'Address', 'ecwp' ), esc_attr( $address_string ) );
		}
	}

	/**
	 * Hooks for the displaying the Event Category.
	 *
	 * @return category
	 *
	 * @param integer $post_id Event ID.
	 * @param string  $tax Taxonomy Name.
	 * @param string  $title Title Name.
	 **/
	public function return_category( $post_id, $tax, $title = '' ) {
		$terms = wp_get_post_terms( $post_id, $tax );
		if ( ! empty( $terms ) && '' !== $tax ) {
			$category = array();
			foreach ( $terms as $term ) {
				$url        = get_term_link( $term, $tax );
				$category[] = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_attr( $term->name ) );
			}
			$allowed = array(
				'a' => array(
					'href' => true,
				),
			);
			return printf( '<div class="ecwp_items"><span class="material-icons">event_note</span><div class="ecwp_item"><div class="ecwp_title">%s</div><p>%s</p></div></div>', esc_attr( $title ), wp_kses( implode( ', ', $category ), $allowed ) );
		}
		return $terms;
	}

	/**
	 * Hooks for the displaying the Event Website.
	 *
	 * @return url
	 *
	 * @param integer $post_id Event ID.
	 **/
	public function return_website( $post_id ) {
		$url_website = get_post_meta( $post_id, $this->event . 'url_website', true );
		if ( '' !== $url_website ) {
			return printf( '<div class="ecwp_items"><span class="material-icons">travel_explore</span><div class="ecwp_item"><div class="ecwp_title">%s</div><a href="%s" target="_blank">%s</a></div></div>', esc_html__( 'Website', 'ecwp' ), esc_url( $url_website ), esc_html__( 'Link to website', 'ecwp' ) );
		}
	}

	/**
	 * Hooks for the displaying the Event Taxonomy Title.
	 *
	 * @param object $term Term Object.
	 **/
	public function return_taxonomy_title( $term ) {
		$page_url  = ( isset( $this->settings['page'] ) && 0 !== $this->settings['page'] ) ? get_permalink( $this->settings['page'] ) : '';
		$page_name = __( 'Events', 'ecwp' );
		printf( "<div class='ecwp_terms'><div class='ecwp_term_title'><a href='%s'>%s</a>/<p>%s</p></div></div>", esc_url( $page_url ), esc_attr( $page_name ), esc_attr( $term->name ) );
	}

	/**
	 * Hooks for the displaying the Add to Calendar Button.
	 *
	 * @return url
	 *
	 * @param integer $post_id Event ID.
	 **/
	public function return_button( $post_id ) {
		return printf( '<div class="ecwp_items" data-title="%s" data-address="%s" id="ecwp_add_calendar"></div>', esc_attr( get_the_title( $post_id ) ), esc_attr( $this->return_address( $post_id, true ) ) );
	}

	/**
	 * Filter Hooks for modifying the time based on the WordPress.
	 *
	 * @return time
	 *
	 * @param timestamp $time Timestamp.
	 **/
	public function return_time( $time ) {
		$wp_date_format = $this->settings['date_format'];
		$wp_time_format = $this->settings['time_format'];
		return esc_attr( wp_date( $wp_date_format . ' ' . $wp_time_format, $time ) );
	}

	/**
	 * Filter Hooks for modifying the location.
	 *
	 * @return venue
	 *
	 * @param integer $venue_id Venue ID.
	 **/
	public function return_venue( $venue_id ) {
		return get_the_title( $venue_id );
	}

	/**
	 * Filter Hooks for modifying the image.
	 *
	 * @param integer $post_id Event ID.
	 * @param string  $size Event Image Size.
	 **/
	public function return_image( $post_id, $size = 'full' ) {
		if ( has_post_thumbnail( $post_id ) ) {
			the_post_thumbnail( $size, array( 'class' => 'ecwp_img' ) );
		}
	}
}

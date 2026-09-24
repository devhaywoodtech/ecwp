<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/admin
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Admin {



	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 * @param      mixed  $settings    The settings of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->settings    = $settings;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * $this->version - Change in the production
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap', false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-picker', plugin_dir_url( __FILE__ ) . 'css/daterangepicker.css', array(), '3.1', 'all' );
		wp_enqueue_style( $this->plugin_name . '-calendar', ECWP_PATH . 'public/css/ecwp-public.css', array( 'wp-components' ), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ecwp-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Admin Admin menu for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=wood-event', __( 'Preview Calendar', 'monthly-events-calendar' ), __( 'Preview Calendar', 'monthly-events-calendar' ), 'manage_options', 'ecwp', array( $this, 'ecwp_admin_dashboard' ) );
		add_submenu_page( 'edit.php?post_type=wood-event', __( 'Settings', 'monthly-events-calendar' ), __( 'Settings', 'monthly-events-calendar' ), 'manage_options', 'ecwp-settings', array( $this, 'ecwp_admin_settings' ) );
	}

	/**
	 * Admin Admin page for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function ecwp_admin_dashboard() {
		require_once ECWP_ADMIN_VIEW . 'ecwp-admin-display.php';
	}

	/**
	 * Admin Admin settings for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function ecwp_admin_settings() {
		require_once ECWP_ADMIN_VIEW . 'ecwp-settings.php';
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ecwp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ecwp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $typenow;
		wp_enqueue_script( 'ecwp-picker', plugin_dir_url( __FILE__ ) . 'js/daterangepicker.js', array( 'jquery', 'moment' ), '3.1', false );
		wp_enqueue_script( 'ecwp', plugin_dir_url( __FILE__ ) . 'js/ecwp-admin.js', array( 'jquery', 'moment' ), $this->version, false );
		wp_localize_script(
			'ecwp',
			'ECWP',
			array(
				'siteurl'  => get_option( 'siteurl' ),
				'rest_url' => get_rest_url(),
				'JSdate'   => ECWP_JS_ADMIN_DATE,
				'WPdate'   => $this->settings['date_format'],
				'WPtime'   => $this->settings['time_format'],
				'timezone' => $this->settings['timezone'],
				'posttype' => $typenow,
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'handle_org' ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
			)
		);
		wp_enqueue_script( $this->plugin_name . '-runtime', ECWP_BUILD . 'runtime~calendar.js', array( 'wp-element', 'wp-i18n' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-calendar', ECWP_BUILD . 'calendar.js', array( 'wp-element', 'wp-i18n' ), $this->version, true );		
		wp_enqueue_script( $this->plugin_name . '-admin', ECWP_BUILD . 'admin.js', array( 'wp-element', 'wp-i18n' ), $this->version, true );
		wp_set_script_translations( $this->plugin_name . '-admin', 'monthly-events-calendar', ECWP_DIR . 'languages' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
	}

	/**
	 * Register the Taxonomy and Post types.
	 *
	 * @since    1.0.0
	 */
	public function register_post_types() {

		$event_slug = ( isset( $this->settings['slug_event'] ) && null !== $this->settings['slug_event'] ) ? $this->settings['slug_event'] : ECWP_EVENTS_SLUG;
		$cat_slug   = ( isset( $this->settings['slug_category'] ) && null !== $this->settings['slug_category'] ) ? $this->settings['slug_category'] : ECWP_CATEGORY_SLUG;
		$tag_slug   = ( isset( $this->settings['slug_tag'] ) && null !== $this->settings['slug_tag'] ) ? $this->settings['slug_tag'] : ECWP_TAG_SLUG;

		$args = array(
			'label'               => esc_html__( 'Events', 'monthly-events-calendar' ),
			'labels'              => array(
				'menu_name'          => esc_html__( 'Events Calendar', 'monthly-events-calendar' ),
				'name_admin_bar'     => esc_html__( 'Event', 'monthly-events-calendar' ),
				'add_new'            => esc_html__( 'Add Event', 'monthly-events-calendar' ),
				'add_new_item'       => esc_html__( 'Add new Event', 'monthly-events-calendar' ),
				'new_item'           => esc_html__( 'New Event', 'monthly-events-calendar' ),
				'edit_item'          => esc_html__( 'Edit Event', 'monthly-events-calendar' ),
				'view_item'          => esc_html__( 'View Event', 'monthly-events-calendar' ),
				'update_item'        => esc_html__( 'View Event', 'monthly-events-calendar' ),
				'all_items'          => esc_html__( 'All Events', 'monthly-events-calendar' ),
				'search_items'       => esc_html__( 'Search Events', 'monthly-events-calendar' ),
				'parent_item_colon'  => esc_html__( 'Parent Event', 'monthly-events-calendar' ),
				'not_found'          => esc_html__( 'No Events found', 'monthly-events-calendar' ),
				'not_found_in_trash' => esc_html__( 'No Events found in Trash', 'monthly-events-calendar' ),
				'name'               => esc_html__( 'Events', 'monthly-events-calendar' ),
				'singular_name'      => esc_html__( 'Event', 'monthly-events-calendar' ),
			),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite_no_front'    => false,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
			'rewrite'             => array( 'slug' => $event_slug ),
		);

		register_post_type( 'wood-event', $args );

		$args = array(
			'label'               => esc_html__( 'Venues', 'monthly-events-calendar' ),
			'labels'              => array(
				'menu_name'          => esc_html__( 'Venues', 'monthly-events-calendar' ),
				'name_admin_bar'     => esc_html__( 'Venue', 'monthly-events-calendar' ),
				'add_new'            => esc_html__( 'Add Venue', 'monthly-events-calendar' ),
				'add_new_item'       => esc_html__( 'Add new Venue', 'monthly-events-calendar' ),
				'new_item'           => esc_html__( 'New Venue', 'monthly-events-calendar' ),
				'edit_item'          => esc_html__( 'Edit Venue', 'monthly-events-calendar' ),
				'view_item'          => esc_html__( 'View Venue', 'monthly-events-calendar' ),
				'update_item'        => esc_html__( 'View Venue', 'monthly-events-calendar' ),
				'all_items'          => esc_html__( 'Venues', 'monthly-events-calendar' ),
				'search_items'       => esc_html__( 'Search Venues', 'monthly-events-calendar' ),
				'parent_item_colon'  => esc_html__( 'Parent Venue', 'monthly-events-calendar' ),
				'not_found'          => esc_html__( 'No Venues found', 'monthly-events-calendar' ),
				'not_found_in_trash' => esc_html__( 'No Venues found in Trash', 'monthly-events-calendar' ),
				'name'               => esc_html__( 'Venues', 'monthly-events-calendar' ),
				'singular_name'      => esc_html__( 'Venue', 'monthly-events-calendar' ),
			),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite_no_front'    => false,
			'show_in_menu'        => 'edit.php?post_type=wood-event',
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'rewrite'             => array( 'slug' => 'venues' ),
		);

		register_post_type( 'wood-venue', $args );

		$args = array(
			'label'               => esc_html__( 'Organizers', 'monthly-events-calendar' ),
			'labels'              => array(
				'menu_name'          => esc_html__( 'Organizers', 'monthly-events-calendar' ),
				'name_admin_bar'     => esc_html__( 'Organizers', 'monthly-events-calendar' ),
				'add_new'            => esc_html__( 'Add Organizers', 'monthly-events-calendar' ),
				'add_new_item'       => esc_html__( 'Add new Organizers', 'monthly-events-calendar' ),
				'new_item'           => esc_html__( 'New Organizers', 'monthly-events-calendar' ),
				'edit_item'          => esc_html__( 'Edit Organizers', 'monthly-events-calendar' ),
				'view_item'          => esc_html__( 'View Organizers', 'monthly-events-calendar' ),
				'update_item'        => esc_html__( 'View Organizers', 'monthly-events-calendar' ),
				'all_items'          => esc_html__( 'Organizers', 'monthly-events-calendar' ),
				'search_items'       => esc_html__( 'Search Organizers', 'monthly-events-calendar' ),
				'parent_item_colon'  => esc_html__( 'Parent Organizers', 'monthly-events-calendar' ),
				'not_found'          => esc_html__( 'No Organizers found', 'monthly-events-calendar' ),
				'not_found_in_trash' => esc_html__( 'No Organizers found in Trash', 'monthly-events-calendar' ),
				'name'               => esc_html__( 'Organizers', 'monthly-events-calendar' ),
				'singular_name'      => esc_html__( 'Organizers', 'monthly-events-calendar' ),
			),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite_no_front'    => false,
			'show_in_menu'        => 'edit.php?post_type=wood-event',
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'rewrite'             => array( 'slug' => 'organizers' ),
		);

		register_post_type( 'wood-organizers', $args );

		$args = array(
			'label'                => esc_html__( 'Event Categories', 'monthly-events-calendar' ),
			'labels'               => array(
				'menu_name'                  => esc_html__( 'Event Categories', 'monthly-events-calendar' ),
				'all_items'                  => esc_html__( 'All Event Categories', 'monthly-events-calendar' ),
				'edit_item'                  => esc_html__( 'Edit Event Category', 'monthly-events-calendar' ),
				'view_item'                  => esc_html__( 'View Event Category', 'monthly-events-calendar' ),
				'update_item'                => esc_html__( 'Update Event Category', 'monthly-events-calendar' ),
				'add_new_item'               => esc_html__( 'Add new Event Category', 'monthly-events-calendar' ),
				'new_item'                   => esc_html__( 'New Event Category', 'monthly-events-calendar' ),
				'parent_item'                => esc_html__( 'Parent Event Category', 'monthly-events-calendar' ),
				'parent_item_colon'          => esc_html__( 'Parent Event Category', 'monthly-events-calendar' ),
				'search_items'               => esc_html__( 'Search Event Categories', 'monthly-events-calendar' ),
				'popular_items'              => esc_html__( 'Popular Event Categories', 'monthly-events-calendar' ),
				'separate_items_with_commas' => esc_html__( 'Separate Event Categories with commas', 'monthly-events-calendar' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove Event Categories', 'monthly-events-calendar' ),
				'choose_from_most_used'      => esc_html__( 'Choose most used Event Categories', 'monthly-events-calendar' ),
				'not_found'                  => esc_html__( 'No Event Categories found', 'monthly-events-calendar' ),
				'name'                       => esc_html__( 'Event Categories', 'monthly-events-calendar' ),
				'singular_name'              => esc_html__( 'Event Category', 'monthly-events-calendar' ),
			),
			'public'               => true,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => true,
			'show_Event Tagcloud'  => true,
			'show_in_quick_edit'   => true,
			'show_admin_column'    => true,
			'show_in_rest'         => true,
			'hierarchical'         => true,
			'query_var'            => true,
			'sort'                 => false,
			'rewrite_no_front'     => false,
			'rewrite_hierarchical' => false,
			'rewrite'              => array( 'slug' => $cat_slug ),
		);
		register_taxonomy( 'wood-category', array( 'wood-event' ), $args );

		$args = array(
			'label'                => esc_html__( 'Event Tags', 'monthly-events-calendar' ),
			'labels'               => array(
				'menu_name'                  => esc_html__( 'Event Tags', 'monthly-events-calendar' ),
				'all_items'                  => esc_html__( 'All Event Tags', 'monthly-events-calendar' ),
				'edit_item'                  => esc_html__( 'Edit Event Tag', 'monthly-events-calendar' ),
				'view_item'                  => esc_html__( 'View Event Tag', 'monthly-events-calendar' ),
				'update_item'                => esc_html__( 'Update Event Tag', 'monthly-events-calendar' ),
				'add_new_item'               => esc_html__( 'Add New Event Tag', 'monthly-events-calendar' ),
				'new_item'                   => esc_html__( 'New Event Tag', 'monthly-events-calendar' ),
				'parent_item'                => esc_html__( 'Parent Event Tag', 'monthly-events-calendar' ),
				'parent_item_colon'          => esc_html__( 'Parent Event Tag', 'monthly-events-calendar' ),
				'search_items'               => esc_html__( 'Search Event Tags', 'monthly-events-calendar' ),
				'popular_items'              => esc_html__( 'Popular Event Tags', 'monthly-events-calendar' ),
				'separate_items_with_commas' => esc_html__( 'Separate Event Tags with commas', 'monthly-events-calendar' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove event tags', 'monthly-events-calendar' ),
				'choose_from_most_used'      => esc_html__( 'Choose most used event tags', 'monthly-events-calendar' ),
				'not_found'                  => esc_html__( 'No Event Tags found', 'monthly-events-calendar' ),
				'name'                       => esc_html__( 'Event Tags', 'monthly-events-calendar' ),
				'singular_name'              => esc_html__( 'Event Tag', 'monthly-events-calendar' ),
			),
			'public'               => true,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => true,
			'show_tagcloud'        => true,
			'show_in_quick_edit'   => true,
			'show_admin_column'    => true,
			'show_in_rest'         => true,
			'hierarchical'         => true,
			'query_var'            => true,
			'sort'                 => false,
			'rewrite_no_front'     => false,
			'rewrite_hierarchical' => false,
			'rewrite'              => array( 'slug' => $tag_slug ),
		);
		register_taxonomy( 'wood-event-tag', array( 'wood-event' ), $args );

		flush_rewrite_rules();
	}

	/**
	 * Remove the Block Editor for Event post type.
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $current_status Current Status from WordPress.
	 * @param mixed $post_type Current post type from WordPress.
	 */
	public function remove_block_editor( $current_status, $post_type ) {
		if ( 'wood-event' === $post_type || 'wood-venue' === $post_type || 'wood-organizers' === $post_type ) {
			return false;
		}
		return $current_status;
	}
}

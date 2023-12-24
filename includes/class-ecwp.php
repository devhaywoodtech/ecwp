<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ecwp
 * @subpackage Ecwp/includes
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ecwp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ECWP_VERSION' ) ) {
			$this->version = ECWP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ecwp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ecwp_Loader. Orchestrates the hooks of the plugin.
	 * - Ecwp_I18n. Defines internationalization functionality.
	 * - Ecwp_Admin. Defines all hooks for the admin area.
	 * - Ecwp_Public. Defines all hooks for the public side of the site.
	 * - Ecwp_Hooks. Defines all hooks for the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ecwp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ecwp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-admin.php';

		/**
		 * The class responsible for defining all inputs or form controls used in the metabox.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-inputs.php';

		/**
		 * The class responsible for defining all actions that occur in the metabox area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-metabox.php';

		/**
		 * The class responsible for retrieving all the postypes.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-posttypes.php';

		/**
		 * The class responsible for defining form controls in the metabox area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-form.php';

		/**
		 * The class responsible for adding admin columns in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-columns.php';

		/**
		 * The class responsible for adding REST functionality.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-ecwp-rest.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-ecwp-public.php';

		/**
		 * The class responsible for adding Hooks.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-ecwp-hooks.php';

		$this->loader = new Ecwp_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ecwp_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Ecwp_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Ecwp_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_settings() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );
		$this->loader->add_filter( 'use_block_editor_for_post_type', $plugin_admin, 'remove_block_editor', 10, 2 );

		$rest = new Ecwp_Rest();
		$this->loader->add_action( 'rest_api_init', $rest, 'register_custom_fields', 10, 2 );
		$this->loader->add_action( 'rest_api_init', $rest, 'register_routes' );
		$this->loader->add_filter( 'rest_wood-event_query', $rest, 'filter_by_date', 10, 2 );
		$this->loader->add_filter( 'rest_wood-event_query', $rest, 'filter_by_upcoming', 11, 2 );
		$this->loader->add_filter( 'rest_wood-event_collection_params', $rest, 'collect_params', 10, 1 );

		$metabox = new Ecwp_Metabox();
		$this->loader->add_action( 'add_meta_boxes', $metabox, 'add' );
		$this->loader->add_action( 'save_post', $metabox, 'save', 10, 2 );
		$this->loader->add_action( 'save_post_wood-organizers', $metabox, 'save_organizer', 10, 2 );
		$this->loader->add_filter( 'postbox_classes_wood-event_ecwp_details', $metabox, 'add_metabox_classes' );
		$this->loader->add_filter( 'postbox_classes_wood-event_ecwp_locaton_details', $metabox, 'add_metabox_classes' );
		$this->loader->add_filter( 'postbox_classes_wood-event_organizer_details_for_event', $metabox, 'add_metabox_classes' );
		$this->loader->add_filter( 'postbox_classes_wood-venue_ecwp_locaton_details', $metabox, 'add_metabox_classes' );
		$this->loader->add_filter( 'postbox_classes_wood-organizers_ecwp_organizer_details', $metabox, 'add_metabox_classes' );
		$this->loader->add_action( 'wp_ajax_org_fields', $metabox, 'org_fields' );

		$columns = new Ecwp_Columns( $this->get_settings() );
		$this->loader->add_filter( 'manage_wood-event_posts_columns', $columns, 'add_event_columns' );
		$this->loader->add_action( 'manage_wood-event_posts_custom_column', $columns, 'event_column_data', 10, 2 );
		$this->loader->add_filter( 'manage_edit-wood-event_sortable_columns', $columns, 'sortable_column', 10, 1 );
		$this->loader->add_action( 'pre_get_posts', $columns, 'event_column_sort', 10, 1 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Ecwp_Public( $this->get_plugin_name(), $this->get_version(), $this->get_settings() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 999 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'category_template', 99 );
		$this->loader->add_filter( 'single_template', $plugin_public, 'single_event_template' );
		add_shortcode( 'wp_monthly_events', array( $plugin_public, 'ecwp_monthly_events' ) );

		$hooks = new Ecwp_Hooks( $this->get_settings() );
		$this->loader->add_action( 'modern_single_template_start', $hooks, 'template_start' );
		$this->loader->add_action( 'modern_single_template_end', $hooks, 'template_end' );
		$this->loader->add_action( 'modern_organizers', $hooks, 'modern_organizers', 10, 1 );
		$this->loader->add_action( 'modern_organizers_details', $hooks, 'modern_organizers_details', 10, 2 );
		$this->loader->add_action( 'modern_single_sidebar', $hooks, 'modern_sidebar' );
		$this->loader->add_action( 'monthly_events_start_date', $hooks, 'return_start_date', 10, 1 );
		$this->loader->add_action( 'monthly_events_end_date', $hooks, 'return_end_date', 10, 1 );
		$this->loader->add_filter( 'monthly_events_time', $hooks, 'return_time' );
		$this->loader->add_action( 'monthly_events_location', $hooks, 'return_location', 10, 1 );
		$this->loader->add_action( 'monthly_events_address', $hooks, 'return_address', 10, 1 );
		$this->loader->add_action( 'monthly_events_category', $hooks, 'return_category', 10, 3 );
		$this->loader->add_filter( 'monthly_events_venue', $hooks, 'return_venue', 10, 1 );
		$this->loader->add_action( 'monthly_events_website', $hooks, 'return_website', 10, 1 );
		$this->loader->add_action( 'monthly_events_button', $hooks, 'return_button', 10, 1 );
		$this->loader->add_filter( 'monthly_events_img', $hooks, 'return_image', 10, 2 );
		$this->loader->add_action( 'modern_taxonomy_template_title', $hooks, 'return_taxonomy_title', 10, 1 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ecwp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the plugin settings.
	 *
	 * @since     1.0.0
	 * @return    mixed    The settings of the plugin.
	 */
	public function get_settings() {
		return get_option( ECWP_SETTINGS );
	}
}

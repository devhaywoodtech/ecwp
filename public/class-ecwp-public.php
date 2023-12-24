<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/public
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 * @param      mixed  $settings    The settings of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->settings    = $settings;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap', false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-micons', 'https://fonts.googleapis.com/icon?family=Material+Icons', false, $this->version );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ecwp-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ecwp-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'ecwp',
			'ECWP',
			array(
				'siteurl'  => get_option( 'siteurl' ),
				'rest_url' => get_rest_url(),
				'JSdate'   => 'MM-DD-YYYY hh:mm A',
				'WPdate'   => $this->settings['date_format'],
				'WPtime'   => $this->settings['time_format'],
				'timezone' => $this->settings['timezone'],
			)
		);
		wp_enqueue_script( $this->plugin_name . '-runtime', ECWP_BUILD . 'runtime~calendar.js', array( 'wp-element' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-calendar', ECWP_BUILD . 'calendar.js', array( 'wp-element' ), $this->version, true );
	}

	/**
	 * Shortcode for displaying calender on
	 * front end.
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $atts Shortcode attributes from the user.
	 */
	public function ecwp_monthly_events( $atts ) {
		$atts = shortcode_atts(
			array(
				'view' => '',
			),
			$atts,
			'ecwp_monthly_events'
		);
		ob_start();
		printf( '<div id="ecwp-calendar" data-display="%s"></div>', esc_attr( $atts['view'] ) );
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	/**
	 * Template for Calendar's Category rendering on
	 * front end.
	 *
	 * @since    1.0.0
	 *
	 * @param mixed $template WordPress Template.
	 */
	public function category_template( $template ) {
		if ( ( is_tax( 'wood-category' ) || is_tax( 'wood-event-tag' ) ) && ! is_admin() ) {
			$custom_template = ECWP_PUBLIC_VIEW . 'ecwp-public-display.php';
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}
		return $template;
	}

	/**
	 * Register the custom post type Event template.
	 *
	 * @param mixed $single_template Template override from the WordPress.
	 *
	 * @return template
	 **/
	public function single_event_template( $single_template ) {
		global $post;

		if ( is_singular( 'wood-event' ) ) {
			$templates = array(
				'single-wood-event' . $post->post_name . '.php', // Custom template based on the post slug.
				'single-wood-event' . $post->ID . '.php', // Custom template based on the post ID.
				'single-wood-event.php', // Generic custom template.
			);

			// Look for the templates in the current theme's directory.
			$located_template = locate_template( $templates );

			// If a custom template is found, use it; otherwise, use the default template.
			if ( $located_template ) {
				return $located_template;
			}

			if ( 'wood-event' === $post->post_type ) {
				$single_template = ECWP_PUBLIC_VIEW . 'single-wood-event.php';
			}
		}

		return $single_template;
	}
}

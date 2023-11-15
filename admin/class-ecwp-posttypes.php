<?php
/**
 * The Posttypes-specific functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/Posttypes
 */

/**
 * The Posttypes-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the Posttypes-specific stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/Posttypes
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Posttypes {
	/**
	 * The name of the postypes.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    Name of the postypes.
	 */
	private $name;

	/**
	 * The title of the postypes.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    Name of the postypes.
	 */
	private $title;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $name   Name of the postypes.
	 * @param    string $title  Title of the post.
	 */
	public function __construct( $name, $title ) {
		$this->name  = $name;
		$this->title = $title;
	}

	/****
	 * Save the Venue or organizer from Event Post type
	 */
	public function save() {
		$post_arr = array(
			'post_title'  => $this->title,
			'post_type'   => $this->name,
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
		);
		return wp_insert_post( $post_arr );
	}
}

<?php
/**
 * The Inputs or form controls specific functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/Ecwp_Inputs
 */

/**
 * The Inputs or form controls specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the Inputs or form controls specific stylesheet and JavaScript.
 *
 * @package    Ecwp
 * @subpackage Ecwp/Ecwp_Inputs
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Inputs {

	/****
	 * Get the Event Inputs or Form Controls for Event Post type
	 *
	 * @param mixed $event Event Slug.
	 */
	public function event_inputs( $event ) {
		$controls = array(
			$event . 'date_range',
			$event . 'date_start',
			$event . 'date_end',
			$event . 'url_website',
			$event . 'text_bg',
		);
		return $controls;
	}

	/****
	 * Get the Venue Inputs or Form Controls for Venue Post type
	 *
	 * @param mixed $venue Venue Slug.
	 */
	public function venue_inputs( $venue ) {
		$controls = array(
			$venue . 'text_address',
			$venue . 'text_city',
			$venue . 'text_state',
			$venue . 'text_country',
			$venue . 'text_postalcode',
			$venue . 'text_phone',
			$venue . 'url_website',
		);
		return $controls;
	}

	/****
	 * Get the Organizer Inputs or Form Controls for Organizer Post type
	 *
	 * @param mixed $organizer Organizer Slug.
	 */
	public function organizer_inputs( $organizer ) {
		$controls = array(
			$organizer . 'text_phone',
			$organizer . 'url_website',
			$organizer . 'email_email',
		);
		return $controls;
	}
}

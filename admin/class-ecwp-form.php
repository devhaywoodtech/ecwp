<?php
/**
 * The form controls functionality of the plugin.
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/form
 */

/**
 * The form controls functionality of the plugin.
 *
 * @package    Ecwp
 * @subpackage Ecwp/form
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Form {
	/**
	 * The name of the form control.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    Name of the form control.
	 */
	private $name;

	/**
	 * The Type of the form control.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type    Type of the form control.
	 */
	private $type;

	/**
	 * The label of the form control.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $type    label of the form control.
	 */
	private $label;

	/**
	 * The class of the form control such as select, checkbox & radio.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $value    keys of the form control .
	 */
	private $classname;

	/**
	 * The value of the form control or the selected value.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      any    $value    value of the form control.
	 */
	private $value;

	/**
	 * The keys of the form control such as select, checkbox & radio.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $value    keys of the form control .
	 */
	private $keys;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $name    The name of the control.
	 * @param      string $type    The type of the control.
	 * @param      string $label   The label of the control.
	 * @param      string $classname   The class of the control.
	 * @param      any    $value   The value of the control.
	 * @param      array  $keys    The keys or array values of the control.
	 * @param      array  $selector    Selector if its multiple or not.
	 */
	public function __construct( $name, $type, $label, $classname = null, $value = null, $keys = array(), $selector = false ) {
		$this->name     = $name;
		$this->type     = $type;
		$this->label    = $label;
		$this->class    = $classname;
		$this->value    = $value;
		$this->keys     = $keys;
		$this->selector = $selector;
		$this->create_control();
	}

	/****
	 * Return the control based upon the type
	 */
	private function create_control() {
		?>
		<div class="ecwp_field <?php echo esc_attr( $this->type ); ?>">
			<label for="<?php echo esc_attr( $this->name ); ?>"><?php echo esc_html( $this->label ); ?></label>
			<?php call_user_func( array( $this, $this->type ) ); ?>
		</div>
		<?php
	}

	/***
	 * Select 2 Control with wp dropdown pages
	 */
	protected function wpdropdown() {
		$name          = ! $this->selector ? $this->name : $this->name . '[]';
		$dropdown_args = array(
			'name'                  => esc_attr( $name ),
			'post_type'             => $this->keys[0],
			'selected'              => esc_attr( $this->value ),
			'class'                 => esc_attr( $this->class ),
			'show_option_none'      => esc_attr( $this->label ),
			'show_option_no_change' => '',
			'option_none_value'     => '',
		);
		wp_dropdown_pages( $dropdown_args ); //phpcs:ignore
	}

	/***
	 * Select 2 Control with Country List
	 */
	protected function country() {
		$country_list = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BQ' => 'Bonaire, Sint Eustatius and Saba',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic of the Congo',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => "Cote D'Ivoire",
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CW' => 'Curacao',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => "Korea, Democratic People's Republic of",
			'KR' => 'Korea, Republic of',
			'XK' => 'Kosovo',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => "Lao People's Democratic Republic",
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia, the Former Yugoslav Republic of',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States of',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'CS' => 'Serbia and Montenegro',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SX' => 'St Martin',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'SS' => 'South Sudan',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan, Province of China',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania, United Republic of',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.s.',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
		printf( "<select name='%s' id='%s' class='postbox ecwp_select wpdropdown %s'>", esc_attr( $this->name ), esc_attr( $this->name ), esc_attr( $this->class ) );
		foreach ( $country_list as $key => $country ) {
			$selected = esc_attr( $this->value ) === esc_attr( $key ) ? 'selected' : '';
			printf( "<option value='%s' %s>%s</option>", esc_attr( $key ), esc_attr( $selected ), esc_attr( $country ) );
		}
		printf( '</select>' );
	}

	/***
	 * Text Input
	 */
	protected function text() {
		$name = ! $this->selector ? $this->name : $this->name . '[]';
		?>
		<input name="<?php echo esc_attr( $name ); ?>" class="postbox ecwp_text <?php echo esc_attr( $this->class ); ?>" type="text" value="<?php echo esc_attr( $this->value ); ?>" placeholder="<?php echo esc_html( $this->label ); ?>" />
		<?php
	}

	/***
	 * Number Input
	 */
	protected function number() {
		$name = ! $this->selector ? $this->name : $this->name . '[]';
		?>
		<input name="<?php echo esc_attr( $name ); ?>" class="postbox ecwp_number <?php echo esc_attr( $this->class ); ?>" type="number" value="<?php echo esc_attr( $this->value ); ?>" placeholder="<?php echo esc_html( $this->label ); ?>" />
		<?php
	}


	/***
	 * Email Input
	 */
	protected function email() {
		$name = ! $this->selector ? $this->name : $this->name . '[]';
		?>
		<input name="<?php echo esc_attr( $name ); ?>" class="postbox ecwp_email <?php echo esc_attr( $this->class ); ?>" type="email" value="<?php echo esc_html( $this->value ); ?>" placeholder="<?php echo esc_html( $this->label ); ?>" />
		<?php
	}

	/***
	 * URL Input
	 */
	protected function url() {
		$name = ! $this->selector ? $this->name : $this->name . '[]';
		?>
		<input name="<?php echo esc_attr( $name ); ?>" class="postbox ecwp_email <?php echo esc_attr( $this->class ); ?>" type="url" value="<?php echo esc_attr( $this->value ); ?>" />
		<?php
	}

	/***
	 * Datepicker Input
	 */
	protected function date() {
		?>
		<input name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->name ); ?>" class="postbox ecwp_date <?php echo esc_attr( $this->class ); ?>" type="text" value="<?php echo esc_attr( $this->value ); ?>" />
		<?php
	}

	/***
	 * Hidden Input
	 */
	protected function hidden() {
		?>
		<input name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->name ); ?>" class="postbox ecwp_date <?php echo esc_attr( $this->class ); ?>" type="hidden" value="<?php echo esc_attr( $this->value ); ?>" />
		<?php
	}

	/***
	 * Timepicker Input
	 */
	protected function time() {
		?>
		<input name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->name ); ?>" class="postbox ecwp_time <?php echo esc_attr( $this->class ); ?>" type="time" value="<?php echo esc_attr( $this->value ); ?>" />
		<?php
	}
}

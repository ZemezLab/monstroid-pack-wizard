<?php
/**
 * Class description
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MPack_Wizard_Updater_API' ) ) {

	/**
	 * Define MPack_Wizard_Updater_API class
	 */
	class MPack_Wizard_Updater_API {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Passed Template ID holder.
		 *
		 * @var int
		 */
		private $template_id = null;

		/**
		 * Passed Order ID holder.
		 *
		 * @var string
		 */
		private $order_id = null;

		/**
		 * Storage for error data.
		 *
		 * @var null
		 */
		private $error = null;

		/**
		 * Endpoint for updated list
		 *
		 * @var string
		 */
		protected $endpoints = array(
			'get'    => 'http://monstroid.cherryframework.com/?order_id=%oid%&template_id=%tid%&action=get_theme',
			'verify' => 'http://monstroid.cherryframework.com/?order_id=%oid%&template_id=%tid%&action=validate_order',
			'themes' => 'http://monstroid.cherryframework.com/themes-list.json',
		);

		/**
		 * Constructor for the class
		 *
		 * @param int    $template_id Template ID from templatemonster.com.
		 * @param string $order_id    Order ID from user order details.
		 */
		function __construct( $template_id = null, $order_id = null ) {
			$this->template_id = $template_id;
			$this->order_id    = $order_id;
		}

		/**
		 * Retrieve a themes list
		 * @return array
		 */
		public function get_themes_list() {

			$transient = 'mpack_themes';
			$themes    = get_transient( $transient );

			if ( ! $themes ) {
				$themes = $this->api_call( 'themes' );
				set_transient( $transient, $themes, HOUR_IN_SECONDS );
			}

			return $themes;
		}

		/**
		 * Returns link to latest template relese
		 *
		 * @return string
		 */
		public function verify_order() {

			$request_data = array(
				'tid' => $this->template_id,
				'oid' => $this->order_id,
			);

			$release_data = $this->api_call( 'verify', $request_data );

			if ( empty( $release_data ) || ! isset( $release_data['success'] ) ) {
				$this->error = esc_html__( 'Internal error, please, try again later', 'monstroid-pack-wizard' );
				return false;
			}

			if ( true !== $release_data['success'] ) {
				$this->error = esc_html__( 'Invalid Order ID', 'monstroid-pack-wizard' );
				return false;
			}

			return $this->build_request_url( 'get', array(
				'tid' => $this->template_id,
				'oid' => $this->order_id,
			) );
		}

		/**
		 * Returns error text
		 *
		 * @return void
		 */
		public function get_error() {
			return $this->error;
		}

		/**
		 * Compare existing releses versions and store larger into $this->ver holder.
		 *
		 * @param  array $item Release data list.
		 * @return void
		 */
		public function _compare_versions( $item ) {
			if ( ! isset( $item['version'] ) ) {
				return;
			}

			if ( version_compare( $item['version'], $this->ver, '>' ) ) {
				$this->ver = $item['version'];
			}
		}

		/**
		 * Perform an API call and return call body.
		 *
		 * @param  string $endpoint Requested endpoint.
		 * @param  array  $data     Request data.
		 * @return array
		 */
		public function api_call( $endpoint, $data = array() ) {

			if ( ! isset( $this->endpoints[ $endpoint ] ) ) {
				return array();
			}

			$request  = $this->build_request_url( $endpoint, $data );
			$response = wp_remote_get( $request );
			$result   = wp_remote_retrieve_body( $response );

			$result = json_decode( $result, true );

			return $result;
		}

		public function build_request_url( $endpoint, $data = array() ) {
			$request = $this->endpoints[ $endpoint ];
			$search  = array_map( array( $this, '_map_macros' ), array_keys( $data ) );
			$replace = array_values( $data );
			return str_replace( $search, $replace, $request );
		}

		/**
		 * Prepare macros
		 *
		 * @param  string $item
		 * @return string
		 */
		public function _map_macros( $item ) {
			return '%' . $item . '%';
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @param  int    $template_id Template ID from templatemonster.com.
		 * @param  string $order_id    Order ID from user order details.
		 * @return object
		 */
		public static function get_instance( $template_id = null, $order_id = null ) {
			return new self( $template_id, $order_id );
		}
	}

}

/**
 * Returns instance of MPack_Wizard_Updater_API
 *
 * @param  int    $template_id Template ID from templatemonster.com.
 * @param  string $order_id    Order ID from user order details.
 * @return object
 */
function mpack_wizard_updater_api( $template_id = null, $order_id = null ) {
	return MPack_Wizard_Updater_API::get_instance( $template_id, $order_id );
}

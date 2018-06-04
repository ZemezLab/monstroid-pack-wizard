<?php
/**
 * Interface management class
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MPack_Wizard_Interface' ) ) {

	/**
	 * Define MPack_Wizard_Interface class
	 */
	class MPack_Wizard_Interface {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Register menu page
		 *
		 * @return void
		 */
		public function register_page() {
			add_management_page(
				esc_html__( 'Monstroid Wizard', 'monstroid-pack-wizard' ),
				esc_html__( 'Monstroid Wizard', 'monstroid-pack-wizard' ),
				'manage_options',
				mpack_wizard()->slug(),
				array( $this, 'render_page' )
			);
		}

		/**
		 * Render Monstroid Wizard page
		 *
		 * @return void
		 */
		public function render_page() {

			$this->get_template( 'page-header.php' );

			$step = ( ! empty( $_GET['step'] ) ) ? $_GET['step'] : 'select-theme';
			$this->get_template( 'step-' . $step . '.php' );

			$this->get_template( 'page-footer.php' );
		}

		/**
		 * Print theme installation URL
		 *
		 * @return void
		 */
		public function install_theme_link( $slug = null ) {

			echo add_query_arg(
				array(
					'theme' => $slug,
				),
				$this->get_page_link( 'verification' )
			);

		}

		/**
		 * Return white listed subpages slugs for wizard.
		 *
		 * @return array
		 */
		public function whitelisted_pages() {
			return array(
				'select-theme',
				'verification',
			);
		}

		/**
		 * Return link to specific wizard step
		 *
		 * @param  string $step Step slug.
		 * @return string
		 */
		public function get_page_link( $step = 'select-theme' ) {

			$base = esc_url( admin_url( 'tools.php' ) );

			return add_query_arg(
				array(
					'page' => esc_attr( mpack_wizard()->slug() ),
					'step' => esc_attr( $step ),
				),
				$base
			);
		}

		/**
		 * Returns URL of succes page.
		 *
		 * @return string
		 */
		public function success_page_link() {
			return apply_filters( 'mpack-wizard/success-redirect-url', $this->get_page_link( 'success' ) );
		}

		/**
		 * Add wizard form row
		 *
		 * @param  array $args Row arguments array
		 * @return void
		 */
		public function add_form_row( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'       => '',
				'field'       => '',
				'placeholder' => '',
			) );

			$format = '<div class="theme-wizard-form__row">
				<label for="%2$s">%1$s</label>
				<input type="text" name="%2$s" id="%2$s" class="wizard-input input-%2$s" placeholder="%3$s">
			</div>';

			printf( $format, $args['label'], $args['field'], $args['placeholder'] );
		}

		/**
		 * Add wizard form row
		 *
		 * @param  array $args Row arguments array
		 * @return void
		 */
		public function add_form_radio( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'   => '',
				'field'   => '',
				'value'   => '',
				'checked' => false,
				'desc'    => '',
			) );

			$format = '<label class="theme-wizard-radio">
				<input type="radio" name="%1$s" value="%2$s" %3$s>
				<span class="theme-wizard-radio__mask"></span>
				<span class="theme-wizard-radio__label">
					<span class="theme-wizard-radio__label-title">%4$s</span>
					<span class="theme-wizard-radio__label-desc">%5$s</span>
				</span>
			</label>';

			printf(
				$format,
				$args['field'],
				$args['value'],
				( true === $args['checked'] ) ? 'checked' : '',
				$args['label'],
				$args['desc']
			);
		}

		/**
		 * Returns button HTML
		 *
		 * @param  array  $args Button arguments.
		 * @return void
		 */
		public function button( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'action' => '',
				'text'   => '',
			) );

			$format = '<button class="btn btn-primary" data-theme-wizard="%1$s">
				<span class="text">%2$s</span>
				<span class="theme-wizard-loader"><span class="theme-wizard-loader__spinner"></span></span>
			</button>';

			return printf( $format, $args['action'], $args['text'] );
		}

		/**
		 * Get plugin template
		 *
		 * @param  string $template Template name.
		 * @param  mixed  $data     Additional data to pass into template
		 * @return void
		 */
		public function get_template( $template, $data = false ) {

			$file = locate_template( mpack_wizard()->slug() . '/' . $template );

			if ( ! $file ) {
				$file = mpack_wizard()->path( 'templates/' . $template );
			}

			$file = apply_filters( 'mpack-wizard/template-path', $file, $template );

			if ( file_exists( $file ) ) {
				include $file;
			}

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of MPack_Wizard_Interface
 *
 * @return object
 */
function mpack_interface() {
	return MPack_Wizard_Interface::get_instance();
}
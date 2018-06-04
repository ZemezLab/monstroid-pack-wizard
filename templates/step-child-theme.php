<?php
/**
 * Install child theme template
 */
$theme_data = get_option( mpack_wizard()->settings['options']['parent_data'] );

if ( ! $theme_data ) {
	echo '<div class="theme-wizard-error">' . esc_html__( 'We can\'t find any inforamtion about installed theme. Plaese, return to previous', 'monstroid-pack-wizard' ) . '</div>';
	return;
}

?>
<h2><?php esc_html_e( 'Use child theme?', 'monstroid-pack-wizard' ); ?></h2>
<div class="desc"><?php
	printf( esc_html__( 'We recommend you to use our child themes generator to get child theme for %s', 'monstroid-pack-wizard' ), $theme_data['ThemeName'] );
?></div>
<div class="theme-wizard-form">
	<div class="theme-wizard-radio-wrap"><?php
		mpack_interface()->add_form_radio( array(
			'label'   => esc_html__( 'Continue with parent theme', 'monstroid-pack-wizard' ),
			'desc'    => esc_html__( 'Skip child theme installation and continute with parent theme.', 'monstroid-pack-wizard' ),
			'field'   => 'use_child',
			'value'   => 'skip_child',
			'checked' => true,
		) );
		mpack_interface()->add_form_radio( array(
			'label'   => esc_html__( 'Use child theme', 'monstroid-pack-wizard' ),
			'desc'    => esc_html__( 'Download and install child theme. Note: we recommend doing this, because it is the most safe way to make future modifictaions.', 'monstroid-pack-wizard' ),
			'field'   => 'use_child',
			'value'   => 'get_child',
		) );
	?></div>
	<?php
		mpack_interface()->button( array(
			'action' => 'get-child',
			'text'   => esc_html__( 'Continue', 'monstroid-pack-wizard' ),
		) );
	?>
</div>
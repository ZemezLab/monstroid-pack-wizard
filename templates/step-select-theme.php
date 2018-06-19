<?php
/**
 * Select theme step
 */
?>
<h2><?php esc_html_e( 'Select Theme', 'monstroid-pack-wizard' ); ?></h2>
<div class="desc"><?php
	esc_html_e( 'Please, select theme you want to install:', 'monstroid-pack-wizard' );
?></div>
<div class="mpack-themes"><?php

	mpack_wizard()->dependencies( array( 'updater-api' ) );

	$api    = mpack_wizard_updater_api();
	$themes = $api->get_themes_list();

	if ( is_array( $themes ) && ! empty( $themes ) ) {

		foreach ( $themes as $theme ) {
			?>
			<div class="mpack-themes__item">
				<div class="mpack-themes__item-content">
					<div class="mpack-themes__item-thumb">
						<img src="<?php echo $theme['thumb']; ?>" alt="">
					</div>
					<h4><?php echo $theme['name']; ?></h4>
					<div class="mpack-themes__item-actions">
						<a href="<?php mpack_interface()->install_theme_link( $theme['slug'] ); ?>" class="mpack-btn btn-primary"><?php
							esc_html_e( 'Install', 'monstroid-pack-wizard' );
						?></a>
						<a href="<?php echo $theme['demo']; ?>" class="mpack-btn btn-default" target="_blank"><?php
							esc_html_e( 'Live Demo', 'monstroid-pack-wizard' );
						?></a>
					</div>
				</div>
			</div>
			<?php
		}

	}

?></div>
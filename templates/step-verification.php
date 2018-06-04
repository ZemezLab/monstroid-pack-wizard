<?php
/**
 * Verification Step template
 */
?>
<h2><?php esc_html_e( 'Install Theme', 'monstroid-pack-wizard' ); ?></h2>
<div class="desc"><?php
	esc_html_e( 'Please, enter your Order ID to verify your access and start installation:', 'monstroid-pack-wizard' );
?></div>
<div class="theme-wizard-form">
	<?php
		mpack_interface()->add_form_row( array(
			'label'       => esc_html__( 'Order ID:', 'monstroid-pack-wizard' ),
			'field'       => 'order_id',
			'placeholder' => esc_html__( 'Enter your order ID here...', 'monstroid-pack-wizard' ),
		) );
		mpack_interface()->button( array(
			'action' => 'start-install',
			'text'   => esc_html__( 'Start Install', 'monstroid-pack-wizard' ),
		) );
	?>
</div>
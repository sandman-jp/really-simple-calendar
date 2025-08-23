<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<form method="post" action="" class="rsc-calendar-form <?php echo esc_attr($panel->is_update ? 'rsc-ajax-update' : ''); ?>">
<?php wp_nonce_field(SMJP_RSC_.$panel->name, SMJP_RSC_.'save_settings_nonce'); ?>
<?php wp_nonce_field(SMJP_RSC_.$panel->name, SMJP_RSC_.'preview_settings_nonce', false); ?>
<input type="hidden" id="<?php echo esc_attr(SMJP_RSC_); ?>setting" name="<?php echo esc_attr(SMJP_RSC_); ?>setting" value="<?php echo esc_attr($panel->name); ?>">
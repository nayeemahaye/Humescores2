<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices(); ?>
    
	<div class="grid">

	<span class="grid__item one-twelfth">&nbsp;</span>

	<div class="calafate-checkout grid__item ten-twelfths palm-one--whole">

		<header class="archive-header active">
				<h1><?php esc_html_e( 'Reset', 'calafate' ); ?></h1>
				<h3><?php esc_html_e( 'password', 'calafate' ); ?></h3>
			</header>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password woocommerce-checkout one-half lap--two-thirds palm--one-whole ccform">

			<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'calafate' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first" style="width: 100%;">
				<label for="password_1"><?php esc_html_e( 'New password', 'calafate' ); ?> <span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" />
			</p>
			<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-last" style="width: 100%;">
				<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'calafate' ); ?> <span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" />
			</p>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

			<div class="clear"></div>

			<?php do_action( 'woocommerce_resetpassword_form' ); ?>

			<p class="woocommerce-FormRow form-row" style="width: 100%; text-align: center;">
				<input type="hidden" name="wc_reset_password" value="true" />
				<input type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'calafate' ); ?>" style="float: none;" />
			</p>

			<?php wp_nonce_field( 'reset_password' ); ?>

		</form>

	</div>

</div>
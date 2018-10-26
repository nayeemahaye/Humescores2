<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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

	<div class="calafate-checkout grid__item ten-twelfths palm--one-whole">

		<header class="archive-header active">
			<h1><?php esc_html_e( 'Lost', 'calafate' ); ?></h1>
			<h3><?php esc_html_e( 'password', 'calafate' ); ?></h3>
		</header>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password woocommerce-checkout one-half lap--two-thirds palm--one-whole ccform">

			<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'calafate' ) ); ?></p>
	 
			<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first" style="width: 100%;">
				<label for="user_login"><?php esc_html_e( 'Username or email', 'calafate' ); ?></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" />
			</p>

			<div class="clear"></div>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<p class="woocommerce-FormRow form-row" style="width: 100%; text-align: center;">
				<input type="hidden" name="wc_reset_password" value="true" />
				<input type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset Password', 'calafate' ); ?>" style="float: none;" />
			</p>

			<?php wp_nonce_field( 'lost_password' ); ?>

		</form>

	</div>

</div>
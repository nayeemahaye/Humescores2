<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
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
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
?>
<div class="grid">

	<span class="grid__item one-twelfth">&nbsp;</span>

	<div class="calafate-checkout grid__item ten-twelfths palm--one-whole">

		<header class="archive-header active">
			<h1><?php esc_html_e( 'Lost', 'calafate' ); ?></h1>
			<h3><?php esc_html_e( 'password', 'calafate' ); ?></h3>
		</header>

		<p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'calafate' ) ); ?></p>

	</div>

</div>
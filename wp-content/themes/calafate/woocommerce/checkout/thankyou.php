<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php woocommerce_breadcrumb(); ?>

<div class="woocommerce-MyAccount-navigation"></div>

<div class="woocommerce-MyAccount-content">

		<header class="archive-header active">
			<h1><?php esc_html_e( 'Thank you', 'calafate' ); ?></h1>
		</header>

		<?php if ( $order ) : ?>

			<?php if ( $order->has_status( 'failed' ) ) : ?>

				<p class="woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'calafate' ); ?></p>

				<p class="woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'calafate' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'calafate' ); ?></a>
					<?php endif; ?>
				</p>

			<?php else : ?>

				<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'calafate' ), $order ); ?></p>

				<ul class="woocommerce-thankyou-order-details order_details">
					<li class="order">
						<?php esc_html_e( 'Order Number:', 'calafate' ); ?>
						<strong><?php echo $order->get_order_number(); ?></strong>
					</li>
					<li class="date">
						<?php esc_html_e( 'Date:', 'calafate' ); ?>
						<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
					</li>
					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php _e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>
					<li class="total">
						<?php esc_html_e( 'Total:', 'calafate' ); ?>
						<strong><?php echo $order->get_formatted_order_total(); ?></strong>
					</li>
					<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="method">
						<?php esc_html_e( 'Payment Method:', 'calafate' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
					<?php endif; ?>
				</ul>
				<div class="clear"></div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php else : ?>

			<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'calafate' ), null ); ?></p>

		<?php endif; ?>


</div>
<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'calafate' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<span class="grid__item one-twelfth old-breakpoint--hide">&nbsp;</span>

		<div class="calafate-checkout grid__item ten-twelfths old-breakpoint--whole">

			<ul class="calafate-checkout-navigation">

				<li class="active"><span class="title"><?php esc_html_e( 'Billing Address', 'calafate' ); ?></span><span class="line"></span></li>
				<li><span class="title"><?php esc_html_e( 'Shipping Address', 'calafate' ); ?></span><span class="line"></span></li>

				<?php if ( WC()->cart->coupons_enabled() ) : ?>
					<li><span class="title"><?php esc_html_e( 'Apply Coupon', 'calafate' ); ?></span><span class="line"></span></li>
				<?php endif; ?>

				<li><span class="title"><?php esc_html_e( 'Review Order', 'calafate' ); ?></span><span class="line"></span></li>

			</ul>

			<?php wc_print_notices(); ?>

			<div class="calafate-checkout-content carousel">

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
			
				<div class="carousel-cell">

					<div class="wrapper"><div class="wrapper-second">

						<h3 class="chck-title"><?php esc_html_e( 'Billing Address', 'calafate' ); ?></h3>
						<h4 class="chck-subtitle"><?php esc_html_e( 'Fill in your details', 'calafate' ); ?></h4>
						<div class="chck-form clearfix"><?php do_action( 'woocommerce_checkout_billing' ); ?></div>
	
						<a class="chck-link" data-slide="1" href="#"><?php esc_html_e( 'Next step', 'calafate' ); ?></a>

					</div></div>

				</div>
			
				<div class="carousel-cell">

					<div class="wrapper"><div class="wrapper-second">

						<h3 class="chck-title"><?php esc_html_e( 'Shipping Address', 'calafate' ); ?></h3>
						<h4 class="chck-subtitle"><?php esc_html_e( 'Fill in your details', 'calafate' ); ?></h4>
						<div class="chck-form clearfix"><?php do_action( 'woocommerce_checkout_shipping' ); ?></div>

						<a class="chck-link" data-slide="2" href="#"><?php esc_html_e( 'Next step', 'calafate' ); ?></a>

					</div></div>

				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php if ( WC()->cart->coupons_enabled() ) : ?>

					<div id="checkout_coupon" class="carousel-cell">

						<div class="wrapper"><div class="wrapper-second">

							<h3 class="chck-title"><?php esc_html_e( 'Apply Coupon', 'calafate' ); ?></h3>
							<h4 class="chck-subtitle"><?php esc_html_e( 'Forgot about your discount?', 'calafate' ); ?></h4>

							<div id="append-coupon-here"></div>

							<a data-slide="3" class="chck-link" href="#"><?php esc_html_e( 'Next step', 'calafate' ); ?></a>

						</div></div>

					</div>

				<?php endif; ?>

				<div id="order_review" class="carousel-cell">

					<div class="wrapper"><div class="wrapper-second">

						<h3 class="chck-title"><?php _e( 'Review your order', 'calafate' ); ?></h3>
						<?php $size = WC()->cart->get_cart_contents_count(); ?>
						<h4 class="chck-subtitle"><?php echo sprintf( esc_html( _n( '1 item in your cart', '%s items in your cart', $size, 'calafate' ) ), $size ); ?></h4>

						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

					</div></div>

				</div>

			</div>

		</div>

	<?php endif; ?>

</form>

<?php if ( WC()->cart->coupons_enabled() ) : ?>
	<div id="move-this-block" class="chck-form clearfix"><?php woocommerce_checkout_coupon_form(); ?></div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
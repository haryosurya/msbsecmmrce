<?php
if ( ! defined( 'ABSPATH' ) ) {
        exit;
}
?>

<div class="woocommerce-order">
        <?php if ( $order ) : ?>

                <?php if ( $order->has_status( 'failed' ) ) : ?>

                        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

                        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
                                <?php if ( is_user_logged_in() ) : ?>
                                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
                                <?php endif; ?>
                        </p>

                <?php else : 
                    $phoneNumber = get_option('woo_wa_phone_number');
                    function isMobile() {
                        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
                    }
                    // If the user is on a mobile device, redirect them
                    if(isMobile()){
                            $link = "https://wa.me/.'$phoneNumber'.?text=$content";
                    }
                    else{
                            $link = "https://web.whatsapp.com/send?phone=.'$phoneNumber'.&text=$content";
                    }
                    $ordernumber = $order->get_order_number();
                    $id = $order->get_id();
                    $orderdate = date_i18n( get_option( 'date_format' ), $order->get_date_created() );
                    $oredertotal = $order->get_formatted_order_total();





                    echo "Order ID : ".$ordernumber;
                    echo "Order IDs : ".$id;
?>

                <?php endif; ?>

                <p>Since this is your first order, we are happy to extend a 10% discount on your next purchase. Use the coupon code <strong>WELCOME10</strong> to avail the discount.</p>

                <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

        <?php else : ?>

                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

        <?php endif; ?>

</div>

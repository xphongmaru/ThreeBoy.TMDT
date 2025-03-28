<?php
/**
 * Help button
 *
 * @package    Wt_Smart_Coupon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wbte_sc_bogo_help">
	<span class="wbte_sc_bogo_help_tooltext"><?php esc_html_e( 'Help', 'wt-smart-coupons-for-woocommerce' ); ?></span>
	<div class="wbte_sc_bogo_help_tooltext_open">
		<a href=<?php echo esc_url( 'https://www.webtoffee.com/woocommerce-bogo-discounts/' ); ?> target="_blank"><p><?php esc_html_e( 'Setup Guide', 'wt-smart-coupons-for-woocommerce' ); ?></p></a>
		<a href=<?php echo esc_url( 'https://www.webtoffee.com/support/' ); ?> target="_blank"><p><?php esc_html_e( 'Contact support', 'wt-smart-coupons-for-woocommerce' ); ?></p></a>
	</div>
	<p>?</p>
</div>
<?php
/**
 * BOGO edit page content
 *
 * @since   2.0.0
 * @package    Wt_Smart_Coupon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . '---wbte-header.php';

$trash_icon = '<span style="height: 24px;"  class="wbte_sc_bogo_edit_trash">' . wp_kses_post( $ds_obj->render_html( array( 'html' => '{{wbte-ds-icon-trash}}' ) ) ) . '</span>';
?>

<form id="wbte_sc_bogo_coupon_save" method="POST">
	<input type="hidden" id="wt_sc_bogo_coupon_id" name="wt_sc_bogo_coupon_id" value="<?php echo esc_attr( $coupon_id ); ?>">
	<div class="wbte_sc_bogo_edit_main">
		<div class="wbte_sc_bogo_edit_content">
			<div class="wbte_sc_bogo_edit_head">
				<img class="wbte_sc_bogo_goback_btn" src="
				<?php
				echo esc_url(
					$ds_obj->get_asset(
						array(
							'name' => 'left-arrow-1',
							'type' => 'icon',
						)
					)
				);
				?>
				" onclick="window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=' . self::$bogo_page_name ) ); ?>'">
				<h3><?php esc_html_e( 'Buy product X, get product X/Y', 'wt-smart-coupons-for-woocommerce' ); ?></h3>
			</div>
			<?php

				$selected_triggers_when = self::get_coupon_meta_value( $coupon_id, 'wbte_sc_bogo_triggers_when' );

				require_once plugin_dir_path( __FILE__ ) . '---step1.php';
				require_once plugin_dir_path( __FILE__ ) . '---step2.php';
				require_once plugin_dir_path( __FILE__ ) . '---step3.php';
			?>
		</div>
		<?php require_once plugin_dir_path( __FILE__ ) . '---edit-general.php'; ?>
	</div>
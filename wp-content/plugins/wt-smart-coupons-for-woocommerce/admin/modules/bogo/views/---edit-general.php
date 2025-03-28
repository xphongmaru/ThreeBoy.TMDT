<?php
/**
 * BOGO other fields in edit page
 * eg:- Schedule, Auto, title, description etc
 *
 * @package    Wt_Smart_Coupon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$start_date       = get_post_meta( $coupon_id, '_wt_coupon_start_date', true );
$end_date         = ! is_null( $coupon->get_date_expires() ) ? $coupon->get_date_expires()->date( 'Y-m-d' ) : '';
$schedule_enabled = ! empty( $start_date ) || ! empty( $end_date );

// Get today's date in 'Y-m-d' format for the 'min' attribute.
$today_date = gmdate( 'Y-m-d' );

?>

	<div class="wbte_sc_bogo_edit_general">

		<div class="wbte_sc_bogo_tab_btn_radio wbte_sc_bogo_edit_gnrl_sts_radio <?php echo isset( $_GET['newly_created'] ) ? ' hide' : ''; ?>">
			<?php
			$_coupon_sts = $coupon->get_status();
			if ( 'publish' !== $_coupon_sts ) {
				$_coupon_sts = 'draft';
			}
			?>
			<span><?php esc_html_e( 'Offer:', 'wt-smart-coupons-for-woocommerce' ); ?></span>&nbsp;
			<div class="wbte_sc_offer_sts_div">
				<label>
					<input type="radio" name="_wbte_sc_bogo_selected_sts" id="_wbte_sc_bogo_selected_sts_publish" value="publish" 
						<?php
						checked( 'publish', $_coupon_sts );
						?>
					/>
					<div class="first box active">
						<span>
							<?php esc_html_e( 'Active', 'wt-smart-coupons-for-woocommerce' ); ?>
						</span>
					</div>
				</label>
				<label>
					<input type="radio" name="_wbte_sc_bogo_selected_sts" id="_wbte_sc_bogo_selected_sts_draft" value="draft" 
						<?php
						checked( 'draft', $_coupon_sts );
						?>
					/>
					<div class="second box inactive">
						<span>
							<?php esc_html_e( 'Inactive', 'wt-smart-coupons-for-woocommerce' ); ?>
						</span>
					</div>
				</label>
			</div>
		</div>
		<br><br>
		<div>
			<label for="wbte_sc_bogo_coupon_name" class="wbte_sc_bogo_input_title"><?php esc_html_e( 'Offer name', 'wt-smart-coupons-for-woocommerce' ); ?></label>
			<?php echo wp_kses_post( wc_help_tip( __( 'The offer title is used to identify a BOGO campaign within the plugin and the store.', 'wt-smart-coupons-for-woocommerce' ) ) ); ?><br>
			<input type="text" id="wbte_sc_bogo_coupon_name" name="wbte_sc_bogo_coupon_name" class="wbte_sc_bogo_text_input" placeholder="<?php esc_attr_e( 'Offer name', 'wt-smart-coupons-for-woocommerce' ); ?>" value="<?php echo esc_html( self::get_coupon_meta_value( $coupon_id, 'wbte_sc_bogo_coupon_name' ) ); ?>">
		</div>
		<br>

		<label for="woocommerce-coupon-description" class="wbte_sc_bogo_input_title"><?php esc_html_e( 'Description', 'wt-smart-coupons-for-woocommerce' ); ?></label>
		<?php echo wp_kses_post( wc_help_tip( __( 'Add a short note to display on the coupon to help customers better understand the rules.', 'wt-smart-coupons-for-woocommerce' ) ) ); ?>
		<br>
		<textarea type="text" id="woocommerce-coupon-description" name="woocommerce-coupon-description" class="wbte_sc_bogo_text_input" placeholder="<?php esc_attr_e( 'Description', 'wt-smart-coupons-for-woocommerce' ); ?>" rows="5" ><?php echo esc_html( $coupon->get_description() ); ?></textarea><br>

		<p><?php esc_html_e( 'Activate offer', 'wt-smart-coupons-for-woocommerce' ); ?></p>

		<?php
		echo $ds_obj->get_component(
			'radio-group multi-line',
			array(
				'values' => array(
					'name'  => 'wbte_sc_bogo_code_condition',
					'items' => array(
						array(
							'label' => sprintf( 
								__( 'Automatically %s %s %s', 'wt-smart-coupons-for-woocommerce' ), 
								wp_kses_post( wc_help_tip( __( 'The offer is automatically applied when eligible products are added to the cart', 'wt-smart-coupons-for-woocommerce' ) ) ), 
								wp_kses_post( '<span class="wbte_sc_bogo_code_copy_container"><span class="wbte_sc_hidden_tooltip">' . __( 'Copy coupon code for admin use', 'wt-smart-coupons-for-woocommerce' ) . '</span><img class="wbte_sc_bogo_code_copy" src="' . esc_url( "{$admin_img_path}copy.svg" ) . '" alt="' . esc_attr__( 'copy code', 'wt-smart-coupons-for-woocommerce' ) . '" /></span>' ), 
								wp_kses_post( '<span class="wbte_sc_bogo_help_text wbte_sc_bogo_code_cond_help_txt">' . __( 'Offer name will be displayed in the cart summary when offer is applied', 'wt-smart-coupons-for-woocommerce' ) . '</span>' ) 
							),
							'value' => 'wbte_sc_bogo_code_auto',
							'is_checked' => 'wbte_sc_bogo_code_auto' === self::get_coupon_meta_value( $coupon_id, 'wbte_sc_bogo_code_condition' ),
						),
						array(
							'label' => sprintf( esc_html__( 'Through coupon code %s', 'wt-smart-coupons-for-woocommerce' ), wp_kses_post( wc_help_tip( __( 'The user must enter the coupon code after adding eligible items to the cart to redeem the offer', 'wt-smart-coupons-for-woocommerce' ) ) ) ),
							'value' => 'wbte_sc_bogo_code_manual',
							'is_checked' => 'wbte_sc_bogo_code_manual' === self::get_coupon_meta_value( $coupon_id, 'wbte_sc_bogo_code_condition' ),
						),
					),
				),
				'class' => array( 'wbte_sc_bogo_edit_code_cond_radio' )
			)
		);
		?>

		<div class=" <?php echo 'wbte_sc_bogo_code_manual' === self::get_coupon_meta_value( $coupon_id, 'wbte_sc_bogo_code_condition' ) ? '' : 'wbte_sc_bogo_conditional_hidden '; ?>">
			<input type="text" id="wbte_sc_bogo_coupon_code" name="wbte_sc_bogo_coupon_code" class="wbte_sc_bogo_text_input" placeholder="<?php esc_attr_e( 'Coupon code', 'wt-smart-coupons-for-woocommerce' ); ?>" value="<?php echo esc_html( $coupon->get_code() ); ?>">
			<br><span class="wbte_sc_bogo_help_text">
			<?php
			esc_html_e(
				'Enter a new coupon code',
				'wt-smart-coupons-for-woocommerce'
			);
			?>
			</span>
			<span class="wbte_sc_bogo_coupon_code_error_span"></span>
		</div>
		<br>

		<div class="wbte_sc_bogo_display_div">
			<?php
				$selected              = $this->get_coupon_meta_value( $coupon_id, '_wc_make_coupon_available', true );
				$selected              = $selected ? explode( ',', $selected ) : array();
				$make_coupon_available = array(
					'my_account' => __( 'My Account', 'wt-smart-coupons-for-woocommerce' ),
					'checkout'   => __( 'Checkout', 'wt-smart-coupons-for-woocommerce' ),
					'cart'       => __( 'Cart', 'wt-smart-coupons-for-woocommerce' ),
				);
				?>
			<p>
				<?php
				esc_html_e( 'Display offer on', 'wt-smart-coupons-for-woocommerce' );
				echo wp_kses_post( wc_help_tip( __( 'The available BOGO offers will be listed on the selected pages', 'wt-smart-coupons-for-woocommerce' ) ) );
				echo wp_kses_post( '<span class="wbte_sc_bogo_selected_display_span">' );
				if ( empty( $selected ) ) {
					echo wp_kses_post( '<span class="wbte_sc_bogo_edit_add_button wbte_sc_bogo_coupon_display_add_btn">' . __( '+ Add', 'wt-smart-coupons-for-woocommerce' ) . '</span>' );
				} else {
					foreach ( $selected as $select ) {
						echo wp_kses_post( '<span class="wbte_sc_bogo_selected_display ' . $select . '">' . $make_coupon_available[ $select ] . '</span>' );
					}
					echo wp_kses_post( '<img src="' . esc_url( $admin_img_path ) . 'edit.svg" alt="' . __( 'Edit', 'wt-smart-coupons-for-woocommerce' ) . '">' );
				}
				echo wp_kses_post( '</span>' );
				?>
			</p>
			<?php
			foreach ( $make_coupon_available as $display_slug => $display_title ) {

				echo $ds_obj->get_component(
					'checkbox normal',
					array(
						'values' => array(
							'name'       => '_wc_make_coupon_available[]',
							'id'         => esc_attr( $display_slug ),
							'value'      => esc_attr( $display_slug ),
							'is_checked' => esc_attr( in_array( $display_slug, $selected, true ) ),
							'label'      => esc_attr( $display_title ),
						),
					)
				);
			}
			?>
		</div>
		<br>
		<div class="wbte_sc_checkbox_container wbte_sc_special_checkbox_container">
			<input type="checkbox" id="wbte_sc_bogo_schedule" name="wbte_sc_bogo_schedule" <?php echo $schedule_enabled ? ' checked' : ''; ?> value="wbte_sc_bogo_schedule">
			<label for="wbte_sc_bogo_schedule"><?php esc_html_e( 'Schedule', 'wt-smart-coupons-for-woocommerce' ); ?>
			<?php echo wp_kses_post( wc_help_tip( __( 'Set a start and end date for your offer. The offer will be active only within this period', 'wt-smart-coupons-for-woocommerce' ) ) ); ?>
		</label>&emsp;
			
		</div>
		<div id="wbte_sc_bogo_schedule_content" <?php echo $schedule_enabled ? '' : ' style=" display: none;"'; ?>>
			<!-- Start on -->
			<label for="_wt_coupon_start_date">
				<p><?php esc_html_e( 'Starts on', 'wt-smart-coupons-for-woocommerce' ); ?></p>
			</label>
			<div class="wbte_sc_schedule_field_row">
				<input type="date" class="wbte_sc_bogo_date_picker" id="_wt_coupon_start_date" name="_wt_coupon_start_date" value="<?php echo ! empty( $start_date ) ? esc_attr( $start_date ) : ''; ?>" min="<?php echo esc_attr( $today_date ); ?>">
			</div>
			<!-- Expiry -->
			<label for="expiry_date">
				<p><?php esc_html_e( 'Ends on', 'wt-smart-coupons-for-woocommerce' ); ?></p>
			</label>
			<div class="wbte_sc_schedule_field_row wbte_sc_schedule_expiry_field_row">
				<input type="date" class="wbte_sc_bogo_date_picker" id="expiry_date" name="expiry_date"  value="<?php echo ! empty( $end_date ) ? esc_attr( $end_date ) : ''; ?>" min="<?php echo esc_attr( $today_date ); ?>">
			</div>
			<div class="wbte_sc_bogo_end_date_warning">
				<img src="<?php echo esc_url( $admin_img_path ); ?>exclamation-triangle.svg" alt="<?php esc_attr_e( 'Expiry date already passed', 'wt-smart-coupons-for-woocommerce' ); ?>">
				<p><?php esc_html_e( 'Set a new end date as the scheduled one has already passed', 'wt-smart-coupons-for-woocommerce' ); ?></p>
			</div>
		</div>
		
		<div class="wbte_sc_bogo_edit_save_buttons">
			<?php
			if ( isset( $_GET['newly_created'] ) ) {
				echo $ds_obj->get_component(
					'button filled medium',
					array(
						'values' => array(
							'button_title' => esc_html__( 'Save & Activate', 'wt-smart-coupons-for-woocommerce' ),
						),
						'class'  => array( 'wbte_sc_bogo_save_and_activate' ),
						'attr'   => array( 'data-btn-id' => 'wbte_sc_bogo_save_and_activate' ),
					)
				);
			}
				echo $ds_obj->get_component(
					'button outlined medium',
					array(
						'values' => array(
							'button_title' => esc_html__( 'Save', 'wt-smart-coupons-for-woocommerce' ),
						),
						'class'  => array( 'wbte_sc_bogo_save_and_draft' ),
						'attr'   => array( 'data-btn-id' => 'wbte_sc_bogo_save_and_draft' ),
					)
				);
				?>
		</div>
	</div>
</form>
import metadata from './block.json';
import { useDispatch, useSelect } from '@wordpress/data';
import { __, sprintf, _n } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';
import { CART_STORE_KEY, VALIDATION_STORE_KEY } from '@woocommerce/block-data';
import { useState, useEffect } from '@wordpress/element';
import { applyCheckoutFilter, extensionCartUpdate } from '@woocommerce/blocks-checkout';


// Global import
const { registerCheckoutBlock } = wc.blocksCheckout;

    
export const WtScBlocksMain = (() => {
    const [couponCode, setCouponCode] = useState('');
    const [isClickBinded, setIsClickBinded] = useState(false);
    const { applyCoupon, removeCoupon } = useDispatch( CART_STORE_KEY );
    const { createErrorNotice, createSuccessNotice } = useDispatch( 'core/notices' );

    if ( ! isClickBinded ) {
        
        /* Set `true` for preventing multiple event binding */
        setIsClickBinded(true);
        
        /* Click event triggered by plugin public JS file */
        document.addEventListener('wt_sc_api_coupon_clicked', function(e){ 

            const couponCode = e.detail.coupon_code;
            const couponId = e.detail.coupon_id;
            const context = 'wc/cart'; /* message context */

            applyCoupon(couponCode)
                .then((response) => { 
                    
                    /* Trigger a custom event */
                    const coupon_click_done_event = new CustomEvent("wt_sc_api_coupon_click_done", {
                        detail:{ 'coupon_code' : couponCode, 'coupon_id': couponId, 'status': true}
                    });
                    document.dispatchEvent(coupon_click_done_event);

                    /* Show success message */
                    if (
                        applyCheckoutFilter( {
                            filterName: 'showApplyCouponNotice',
                            defaultValue: true,
                            arg: { couponCode, context },
                        } )
                    ) {
                        createSuccessNotice(
                            sprintf(
                                /* translators: %s coupon code. */
                                __('Coupon code "%s" has been applied to your cart.', 'wt-smart-coupons-for-woocommerce'),
                                couponCode
                            ),
                            { id: 'coupon-form', type: 'snackbar', context }
                        );
                    }

                })
                .catch((error) => {            
                    
                    /* Trigger a custom event */
                    const coupon_click_done_event = new CustomEvent("wt_sc_api_coupon_click_done", {
                        detail:{ 'coupon_code' : couponCode, 'coupon_id': couponId, 'status': false, 'message': error.message}
                    });
                    document.dispatchEvent(coupon_click_done_event);

                    /* Show error message */
                    createErrorNotice(
                        error.message,
                        {
                            id: 'coupon-form',
                            type: 'snackbar',
                            context,
                        }
                    );
                });    
        });

    }
    
    return '';
});

const options = {
    metadata,
    component: WtScBlocksMain
};

registerCheckoutBlock( options );

/**
 * In WooCommerce, there is an option to set up specific payments only for specific shipping methods. For example, COD is only for 'free shipping'; if the user selected a shipping method other than 'free shipping,' the 'COD' will be removed, but it's not updating the session, which affects the coupon validation check.
 Another scenario is that WooCommerce has an option set to a specific shipping method only for a specific postal code or address. When there is a change in these shipping method changes, it can also affect a change in the payment method (as mentioned in the first scenario).
 The below code is that it will update the session whenever there is a change in the payment method. 
 */

/** Track previous payment method */
let prev_payment_method = null;

/** Subscribe to payment method changes */
wp.data.subscribe( () => {
    const currentPaymentMethod = wp.data.select( 'wc/store/payment' ).getActivePaymentMethod();
    
    /** Only proceed if payment method has changed */
    if ( currentPaymentMethod && prev_payment_method !== currentPaymentMethod ) {
        /** Update previous payment method */
        prev_payment_method = currentPaymentMethod;
        
        /** Update cart with new payment method */
        wp.data.dispatch( 'wc/store/cart' ).applyExtensionCartUpdate( {
            namespace: 'wbte-sc-blocks-update-cart-payment-session',
            data: {
                payment_method: currentPaymentMethod,
            },
        } );
    }
} );
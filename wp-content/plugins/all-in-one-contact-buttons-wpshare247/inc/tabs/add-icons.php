<tr id="ws247-aio-pro-row-add-btn" valign="top" class="tr-icon-group ws247-aio-pro-row">
    <th scope="row">
        
    </th>
    <td>
        <btton data-fancybox="" data-src="#dialog-ws247-aio-pro-icons" type="button" id="ws247-aio-pro-add-icon" class="button"><span class="dashicons dashicons-plus-alt2"></span> <?php _e("Thêm mới icon", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></btton>

        <div id="dialog-ws247-aio-pro-icons" style="display:none; width: 1140px; max-height: 600px;">
            <div class="dialog-ws247-aio-pro-container">
                <h2>Thêm icon của bạn</h2>
                <div class="ws247-aio-pro-form" style="display:none;">
                    <input type="text" id="ws247-aio-pro-icon-link" value="https://" placeholder="https://">
                    <input type="text" id="ws247-aio-pro-icon-text" placeholder="Text icon">
                    <input type="checkbox" id="ws247-aio-pro-icon-checkbox">
                    <label for="ws247-aio-pro-icon-checkbox">Ẩn icon</label>
                    <?php
                    $btn_add_id = apply_filters( 'ws247_aio_ct_btn_add_id', 'js-ajx-add-icon' );
                    ?>
                    <button type="button" class="button" id="<?php echo esc_html($btn_add_id); ?>">Thêm</button>
                </div>


                <div class="ws247-aio-pro-list-icons">
                    <input type="text" id="ws247-aio-pro-icon-search" placeholder="Tìm icon">
                    <?php 
                    $cached_file = WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/tabs/icons-cached-fontawesome-470.php';
                    if(file_exists($cached_file)){
                        require $cached_file;
                    }
                    ?>
                </div>
            </div>
        </div>

    </td>
</tr>
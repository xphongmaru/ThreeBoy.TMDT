<table id="tab_pro_general" class="form-table aeiwooc-tab active">
    <!--List field here .....-->
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <div style="color: #fff; padding: 10px; margin-bottom: 10px; background: #00aff2;">
                <strong>Hướng dẫn sử dụng tại link này <a style="text-decoration: none;color: #ffffff;
" href="https://www.youtube.com/watch?v=eDXc36xI9E0" target="_blank"><span class="dashicons dashicons-video-alt3"></span> Video</a></strong>
            </div>
        </th>
    </tr>

    <?php 
    if(!Ws247_aio_ct_button::is_activated_rel_plugin()){
    ?>
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <div style="color: #fff; padding: 10px; margin-bottom: 10px; background: #ff9800;">
                <btton data-fancybox="" data-src="#dialog-ws247-aio-pro-noti-show" type="button" id="ws247-aio-pro-add-icon" class="button"><?php _e("Bản PRO", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></btton> <img height="30" src="<?php echo WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL; ?>/loading.svg">
            </div>
            <?php require WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/tabs/pro-show.php'; ?>
        </th>
    </tr>
    <?php 
    }
    ?>

    <?php do_action( 'ws247_aio_tr_before' ); ?>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><span class="dashicons dashicons-arrow-right-alt"></span> <?php esc_html_e("Hotline ring shake", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hotline", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            $field_rel = $field;
            ?>
            <input placeholder="Số điện thoại" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            
            <div class="checkbox-group-div" style="margin-top:10px;">
                <span class="checkbox-span">
                    <?php 
                    $field_name = 'is_zalo_shake_hotline'; 
                    $field = Ws247_aio_ct_button::create_option_prefix($field_name);
                    $hide = Ws247_aio_ct_button::class_get_option($field_name);
                    ?>
                    <input data-rel="<?php echo esc_html($field_rel); ?>" <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Zalo link?", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            jQuery("#<?php echo esc_html($field); ?>").click(function(event) {
                                var zalo_input_id = "#"+jQuery(this).data('rel');
                                if( jQuery(this).is(":checked") ){
                                    jQuery(zalo_input_id).attr('placeholder', 'https://zalo.me/tbayvn');
                                }else{
                                    jQuery(zalo_input_id).attr('placeholder', '0852080383');
                                }
                                
                            });
                        });
                    </script>
                </span>

                <span class="checkbox-span">
                    <?php 
                    $field_name = 'hide_shake_hotline'; 
                    $field = Ws247_aio_ct_button::create_option_prefix($field_name);
                    $hide = Ws247_aio_ct_button::class_get_option($field_name);
                    ?>
                    <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
                </span>

                <span class="checkbox-span">
                    <?php 
                    $field_name = 'hide_hotline_number_only'; 
                    $field = Ws247_aio_ct_button::create_option_prefix($field_name);
                    $hide = Ws247_aio_ct_button::class_get_option($field_name);
                    ?>
                    <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Chỉ ẩn số điện thoại", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
                </span>
            </div>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hotline position", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline_pos'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $shake_hotline_pos = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>">
                <option <?php if($shake_hotline_pos=='1') echo 'selected'; ?> value="1"><?php esc_html_e("Bottom left", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <option <?php if($shake_hotline_pos=='2') echo 'selected'; ?> value="2"><?php esc_html_e("Bottom right", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
            </select>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Bottom", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline_bottom';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="15, 20, ...." type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($val); ?>" /> px
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><span class="dashicons dashicons-arrow-right-alt"></span> <?php esc_html_e("Icon Group", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Text contact", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'text_contact'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Liên hệ" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            
            <?php 
            $field_name = 'text_contact_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '#ffffff';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />

            <br/>
            <?php 
            $field_name = 'text_contact_bottom'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="15, 20, ...." type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" /> px
            <small>(<?php esc_html_e("Bottom", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>)</small>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row" colspan="2"><div style="color:red; text-transform: uppercase;"><?php esc_html_e("Kéo thả Icon để thay đổi thứ tự", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?> <span class="dashicons dashicons-editor-ol"></span></div></th>
    </tr>

    <?php do_action( 'ws247_aio_ct_add_before' ); ?>

    <?php do_action( 'ws247_aio_ct_add_my_oicons' ); ?>

    <?php do_action( 'ws247_aio_ct_add_icons' ); ?>

    <?php do_action( 'ws247_aio_ct_add_after' ); ?>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Icon Group position", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_pos'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $icons_pos = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>">
                <option <?php if($icons_pos=='1') echo 'selected'; ?> value="1"><?php esc_html_e("Bottom right", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <option <?php if($icons_pos=='2') echo 'selected'; ?> value="2"><?php esc_html_e("Bottom left", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
            </select>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Bottom", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_bottom';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="15, 20, ...." type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($val); ?>" /> px
        </td>
    </tr>

    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Icon bên trái", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icon_text_on_left'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon bên trái", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Icon group hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'hide_icons'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon group hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hide text icon", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'hide_text_icons'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Hide text icon", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Ngang trên Mobile", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_verticle'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Canh ngang trên điện thoại", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
            <div style="margin-top:10px;"><img src="<?php echo WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL; ?>/mobile-icon-verticle.png" /></div>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hiệu ứng Icon", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_animation'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $animation_val = Ws247_aio_ct_button::class_get_option($field_name);

            $arr_animations = array(
                            '',
                            'all-ft-animation',
                            'ws247-aio-ct-cricle-fade', 
                            'ws247-aio-ct-cricle-zoom');

            $arr_animations = apply_filters( 'ws247_aio_icon_animations', $arr_animations );

            $data_cl = implode(' ', $arr_animations);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" data-cl="<?php echo esc_html($data_cl); ?>">
                <?php 
                foreach ($arr_animations as $k => $animation) {
                    if(!$k){
                        $animation_name = 'Không có';
                    }else{
                        $animation_name = "Hiệu ứng ".$k;
                    }
                    ?>
                    <option <?php if($animation_val==$animation) echo 'selected'; ?> value="<?php echo esc_attr($animation); ?>"><?php esc_html_e($animation_name, WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
    </tr>

    <?php do_action( 'ws247_aio_ct_icons_group_after' ); ?>

    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><span class="dashicons dashicons-arrow-right-alt"></span> <?php esc_html_e("Hộp chat Zalo", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>

    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Zalo OA ID", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'zalo_oa_id';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="535345342424234" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($val); ?>" /> <span>(Zalo Official Account)</span>
            <div style="margin-top:10px;">Cách lấy Zalo OA ID <a target="_blank" href="https://website366.com/cach-lay-zalo-oa-id/">Xem Hướng Dẫn</a></div>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><span class="dashicons dashicons-arrow-right-alt"></span> <?php esc_html_e("Talkto", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Talkto embed", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'talkto_embed';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <textarea style="width:100%;" placeholder="" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>"><?php echo esc_attr($val); ?></textarea>
        </td>
    </tr>

    
    
     <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><span class="dashicons dashicons-arrow-right-alt"></span> <?php esc_html_e("Advanced", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Primary color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'primary_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Hover color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'hover_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Text color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'text_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><?php esc_html_e("Vùng chứa", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <!-- https://webgradients.com/ -->
        <td>
            <?php 
            $field_name = 'container_style'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name); 
            $container_stylef = $field;
            $container_style = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>">
                <option <?php if($container_style=='ft-pn-s') echo 'selected'; ?> value="ft-pn-s"><?php esc_html_e("Mặc định", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <option <?php if($container_style=='ft-pn-s0') echo 'selected'; ?> value="ft-pn-s0"><?php esc_html_e("Không dùng", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <?php 
                for ($i=1; $i <= 5; $i++) { 
                    $si = 'ft-pn-s'.$i;
                    ?>
                    <option <?php if($container_style==$si) echo 'selected'; ?> value="<?php echo $si; ?>"><?php esc_html_e("Kiểu ".$i, WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                    <?php
                }
                ?>

                <option <?php if($container_style=='ft-pn-sn') echo 'selected'; ?> value="ft-pn-sn"><?php esc_html_e("Tùy chọn màu", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
            </select>

            <div id="container_style_custom" style="margin-top: 10px;display:<?php if($container_style=='ft-pn-sn') echo 'block'; else echo 'none'; ?>;">
                <div>
                    <?php 
                    $field_name = 'ft_color_1';
                    $field = Ws247_aio_ct_button::create_option_prefix($field_name);
                    $val = Ws247_aio_ct_button::class_get_option($field_name);
                    if(!$val) $val = '#ffffff';
                    ?>
                    <label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Màu 1", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
                    <input value="<?php echo esc_attr($val); ?>" class="colorpicker custom-color-bg" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
                </div>

                <div>
                    <?php 
                    $field_name = 'ft_color_2';
                    $field = Ws247_aio_ct_button::create_option_prefix($field_name);
                    $val = Ws247_aio_ct_button::class_get_option($field_name);
                    if(!$val) $val = '#000000';
                    ?>
                    <label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Màu 2", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
                    <input value="<?php echo esc_attr($val); ?>" class="colorpicker custom-color-bg" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
                </div>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    jQuery("#<?php echo esc_html($container_stylef); ?>").change(function(event) {
                       var s = jQuery(this).val();
                       if(s=='ft-pn-sn'){
                            jQuery("#container_style_custom").show();
                       }else{
                            jQuery("#container_style_custom").hide();
                       }
                    });
                });
            </script>
        </td>
    </tr>


    <tr valign="top">
        <th scope="row"><?php esc_html_e("Ẩn trước", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'is_hide_first'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Ẩn trước khi trang web được tải", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
        </td>
    </tr>

    <?php do_action( 'ws247_aio_tr_after' ); ?>
    
</table>
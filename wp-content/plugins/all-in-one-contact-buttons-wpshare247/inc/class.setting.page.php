<?php
if( !class_exists('Ws247_aio_ct_button') ):
	class Ws247_aio_ct_button{
		 const FIELDS_GROUP = 'Ws247_aio_ct_button-fields-group'; 
		 const REL_PLUGIN = 'all-in-one-contact-buttons-wpshare247-pro';
		 
		/**
		 * Constructor
		 */
		function __construct() {
			if(is_admin()){
				$this->slug = WS247_AIO_CT_BUTTON_SETTING_PAGE_SLUG;
				$this->option_group = self::FIELDS_GROUP;
				$this->setting_page_url = admin_url('admin.php?page='.$this->slug);

				add_action('admin_head', array( $this, 'admin_head' ) );
				add_action('admin_menu',  array( $this, 'add_setting_page' ) );
				add_action('admin_init', array( $this, 'register_plugin_settings_option_fields' ) );
				add_action('admin_enqueue_scripts', array( $this, 'register_admin_css_js' ));
				add_filter('plugin_action_links', array( $this, 'add_action_link' ), 999, 2 );
				add_action('plugins_loaded', array( $this, 'load_textdomain' ) );
				add_action('activated_plugin', array( $this, 'activated_plugin'), 10, 2 );
				add_action('ws247_aio_ct_add_my_oicons', array( $this, 'ws247_aio_ct_add_my_oicons'), 10);
				add_action('ws247_aio_ct_add_before', array( $this, 'ws247_aio_ct_add_before') );
				add_action('ws247_aio_ct_add_after', array( $this, 'ws247_aio_ct_add_after') );
				$this->old_option_update();
			}
		}

		public function ws247_aio_ct_add_before(){
			require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/tabs/icons-before.php';
		}

		public function ws247_aio_ct_add_after(){
			require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/tabs/icons-after.php';
		}

		public function activated_plugin( $plugin, $network_activation ) {
			if( $plugin == plugin_basename( WS247_AIO_CT_BUTTON ) ) {
				exit( wp_redirect( $this->setting_page_url ) );	        
		    }
		}

		static function is_activated_rel_plugin(){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( self::REL_PLUGIN.'/'.self::REL_PLUGIN.'.php' ) ) {
			    return true;
			}
			return false;
		}

		public function admin_head(){
			?>
			<!-- Ws247_aio_ct_button Plugin -->
			<style type="text/css">
				.tr-icon-group .dashicons{
					color: #FF5722;
				}
				.tr-icon-group .dashicons.dashicons-phone{
					transform: rotate(120deg);
				}
			</style>
			<?php
		}
		
		public function add_action_link( $links, $file  ){
			$plugin_file = basename ( dirname ( WS247_AIO_CT_BUTTON ) ) . '/'. basename(WS247_AIO_CT_BUTTON, '');
			if($file == $plugin_file){
				$setting_link = '<a href="' . admin_url('admin.php?page='.WS247_AIO_CT_BUTTON_SETTING_PAGE_SLUG) . '">'.__( 'Settings' ).'</a>';
				array_unshift( $links, $setting_link );
			}
			return $links;
		}
		
		public function register_admin_css_js(){
			$ver = time();
			
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( 'wp-color-picker');
    		

			wp_enqueue_style( WS247_AIO_CT_BUTTON_PREFIX.'fontawesome-470', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/js/font-awesome-4.7.0/css/font-awesome.min.css', false, '4.7.0' );
    		wp_enqueue_style( WS247_AIO_CT_BUTTON_PREFIX.'jquery.fancybox.min.css', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/js/fancybox/dist/jquery.fancybox.min.css', false, '3.5.7' );
			wp_enqueue_script( WS247_AIO_CT_BUTTON_PREFIX.'jquery.fancybox.min.js', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/js/fancybox/dist/jquery.fancybox.min.js', array('jquery'), '3.5.7' );


    		wp_enqueue_style( WS247_AIO_CT_BUTTON_PREFIX.'aio_ct_button_admin_css', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/aio_ct_button_admin_css.css', false, $ver );

			wp_enqueue_script( WS247_AIO_CT_BUTTON_PREFIX.'admin_aio_ct_button', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/admin_aio_ct_button.js', array('jquery'), $ver );
		}
		
		public function add_setting_page() {
			add_submenu_page( 
				'options-general.php',
				__("Setting", WS247_AIO_CT_BUTTON_TEXTDOMAIN),
				__("Configure Aio Contact", WS247_AIO_CT_BUTTON_TEXTDOMAIN),
				'manage_options',
				$this->slug,
				array($this, 'the_content_setting_page')
			);
		}
		
		public function load_textdomain(){
			load_plugin_textdomain( WS247_AIO_CT_BUTTON_TEXTDOMAIN, false, dirname( plugin_basename( WS247_AIO_CT_BUTTON ) ) . '/languages/' ); 
		}
		
		static function create_option_prefix($field_name){
			return WS247_AIO_CT_BUTTON_PREFIX.$field_name;
		}
		
		public function get_option($field_name){
			return get_option(WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		static function class_get_option($field_name){
			return get_option(WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		public function register_field($field_name){
			register_setting( $this->option_group, WS247_AIO_CT_BUTTON_PREFIX.$field_name);
		}
		
		public function register_plugin_settings_option_fields() {
			/***
			****register list fields
			****/
			$arr_register_fields = array(
											//-------------------------------general tab
											'shake_hotline', 'hide_shake_hotline', 'shake_hotline_pos',
											'icon_fb_messenger','hide_icon_fb_messenger',
											'company_zalo', 'hide_company_zalo',
											'stt_email', 'hide_stt_email', 'stt_hotline',
											'hide_stt_hotline', 'icon_google_map', 'hide_icon_google_map', 'icons_pos',
											'hide_icons', 'text_fb_messenger','text_icon_google_map','text_stt_hotline',
											'text_stt_email', 'text_company_zalo', 'primary_color', 'hover_color',
											'text_color', 'icons_bottom', 'shake_hotline_bottom','talkto_embed','text_contact',
											'text_contact_color', 'is_zalo_shake_hotline',
											'company_tiktok', 'text_company_tiktok', 'hide_company_tiktok',
											'hide_text_icons', 'container_style', 'ft_color_1', 'ft_color_2',
											'icons_verticle', 'is_hide_first', 
											'company_instagram', 'text_company_instagram', 'hide_company_instagram',
											'company_telegram', 'text_company_telegram', 'hide_company_telegram',
											'icon_text_on_left','text_icon_fb_messenger', 'hide_text_company_zalo',
											'hide_text_icon_fb_messenger','hide_text_company_tiktok',
											'hide_text_stt_email', 'hide_text_stt_hotline','hide_text_company_instagram',
											'hide_text_company_telegram','hide_text_icon_google_map',
											'text_contact_bottom', 'zalo_oa_id', 'icons_animation',
											'hide_hotline_number_only', 
											'icon_youtube', 'text_icon_youtube', 'hide_icon_youtube'
										);

			//----------------
			$arr_register_fields = apply_filters( 
											'ws247_aio_register_field', 
											$arr_register_fields 
										);

			if($arr_register_fields){
				foreach($arr_register_fields as $key){
					$this->register_field($key);
				}
			}

			//----------------
			$arr_cust_sys_fields = array();
			$arr_cust_sys_fields = apply_filters( 
											'ws247_aio_register_sys_field', 
											$arr_cust_sys_fields 
										);
			if(is_array($arr_cust_sys_fields)){
				foreach($arr_cust_sys_fields as $key){
					$this->register_field($key);
					$this->register_field('text_'.$key);
					$this->register_field('hide_'.$key);
				}
			}
		}
		
		public function the_content_setting_page(){
			require_once WS247_AIO_CT_BUTTON_PLUGIN_INC_DIR . '/option-form-template.php';
		}

		static function get_arr_icons(){
			$arr_ = array(
							'company_zalo' 
								=> array(
									'dashicons-share', 'Zalo', 'Số điện thoại Zalo', 'Nhắn tin Zalo', 'ws247-icon-zalo'
								),

							'icon_fb_messenger'
								=> array(
									'dashicons-facebook-alt', 'Messenger', 'https://www.messenger.com/t/fanpage123', 'Nhắn tin Messenger', 'ws247-icon-messenger'
								),

							'company_tiktok'
								=> array('dashicons-share', 'Tiktok', 'https://www.tiktok.com/@tbayvn', 'Tiktok link','wp247-icon-tiktok', '<i class="fa-brands fa-tiktok"></i>'
							),

							'stt_email'
								=> array(
									'dashicons-email', 'Email', 'email@gmail.com', 'Email: email@gmail.com','ws247-icon-envelope', '<i class="fas fa-envelope" aria-hidden="true"></i>'
								),

							'stt_hotline'
								=> array(
									'dashicons-phone', 'Hotline', '0852.080383', 'Gọi: 0852.080383', 'ws247-icon-phone',
									'<i class="fas fa-phone" aria-hidden="true"></i>'
								),

							'company_instagram'
								=> array(
									'dashicons-instagram', 'Instagram', 'https://www.instagram.com/tbayvn', 'Instagram link', 'wp247-icon-instagram', '<i class="fa-brands fa-instagram"></i>'
								),

							'company_telegram'
								=> array(
									'dashicons-share', 'Telegram', 'https://www.telegram.com/tbayvn', 'Telegram link','wp247-icon-telegram', '<i class="fa-brands fa-telegram"></i>'
								),

							'icon_google_map'
								=> array(
									'dashicons-location', 'Google', 'Địa chỉ công ty', 'Chỉ đường bản đồ', 
									'ws247-icon-map', '<i class="fas fa-map-marker" aria-hidden="true"></i>'
								),

							'icon_youtube'
								=> array(
									'dashicons-youtube', 'Youtube', 'https://www.youtube.com/@hocwordpress/videos', 'Kênh Youtube', 
									'ws247-icon-youtube', '<i class="fa-brands fa-youtube"></i>'
								),
						);

			return apply_filters( 'ws247_aio_ct_arr_items', $arr_ );
		}

		public function ws247_aio_ct_add_my_oicons(){
			$s_tr_html = '';
			$arr_field = self::get_arr_icons();

			$arr_ = apply_filters( 'ws247_aio_ct_add_arr_icons', $arr_field );

			ob_start();
			foreach ($arr_ as $field_name => $arr_item) { 
					$is_custom_f = isset($arr_item['custom']) ? $arr_item['custom'] : 0; 

					if($is_custom_f){
						$field = $field_name.'_link';
						$link = $arr_item['link'];

						$tr_id = "aioprosortable-".$field_name;
						$font_i = $arr_item['font_i'];
						$datakey = $field_name;
						$dashicons = '';
						$label = $arr_item['name'];
						$placeholder = 'https://'; $text_placeholder = '';
						$name_f = $field_name.'[]';
						$txt_field = $field_name.'_txt';
						$txt_field_name = $name_f;
						$txt = $arr_item['txt'];

						$hide_field = $field_name.'_hide';
						$hide_field_name = $name_f;
				        $hide = $arr_item['hide'];
					}else{
						$field = self::create_option_prefix($field_name);
						$link = self::class_get_option($field_name);

						$tr_id = "aioprosortable-".$field;
						$font_i = '';
						$datakey = $field;
						$dashicons = $arr_item[0];
						if(!$dashicons && isset($arr_item[5])){
							$font_i = $arr_item[5];
						}
						$label = $arr_item[1];
						$placeholder = $arr_item[2];
						$text_placeholder = $arr_item[3];
						$name_f = $field;
						$txt_field = self::create_option_prefix('text_'.$field_name);
						$txt_field_name = $txt_field;
						$txt = self::class_get_option('text_'.$field_name);

				        $hide_field = self::create_option_prefix('hide_'.$field_name);
				        $hide_field_name = $hide_field;
				        $hide = self::class_get_option('hide_'.$field_name);
					}
					?>
					<tr id="<?php echo esc_attr($tr_id); ?>" data-key="<?php echo esc_attr($datakey); ?>" valign="top" class="tr-icon-group">
					    <th scope="row">
					        <span class="dashicons <?php echo $dashicons; ?>"><?php echo $font_i; ?></span> <?php esc_html_e($label, WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
					    </th>
					    <td>
					        <input placeholder="<?php echo esc_attr($placeholder); ?>" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($name_f); ?>" value="<?php echo esc_attr($link); ?>" />

					        <input placeholder="<?php echo esc_attr($text_placeholder); ?>" type="text" id="<?php echo esc_html($txt_field); ?>" name="<?php echo esc_html($txt_field_name); ?>" value="<?php echo esc_attr($txt); ?>" />

					        <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($hide_field); ?>" name="<?php echo esc_html($hide_field_name); ?>" /><label for="<?php echo esc_html($hide_field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
					        <?php 
					        if($is_custom_f):
					        ?>
					        <a href="#" data-id="<?php echo $tr_id; ?>" class="js-ajax-aio-ct-pro-del">Xóa</a>
					    	<?php endif; ?>
					    </td>
					</tr>
					<?php
			}
			$s_tr_html = ob_get_contents();
			ob_end_clean();

			$s_tr_html = apply_filters( 'ws247_aio_ct_add_my_oicons_hook', $s_tr_html );

			echo $s_tr_html;
		}
		
		

		public function old_option_update(){
			$option_old_update = self::class_get_option('option_old_update');
			if($option_old_update==1) return true;

			$arr_old_fields = array( // 'old' => 'new'
									'text_fb_messenger' => 'text_icon_fb_messenger'
								);

			if($arr_old_fields){
				foreach ($arr_old_fields as $old => $new) {
					$old_val = self::class_get_option($old);
					if($old_val !== false){
						$new_field = self::create_option_prefix($new);
						update_option( $new_field, $old_val );
						delete_option( self::create_option_prefix($old) );
					}
				}
				update_option( self::create_option_prefix('option_old_update'), 1 );
			}
		}

	//End class--------------	
	}
	
	new Ws247_aio_ct_button();
endif;

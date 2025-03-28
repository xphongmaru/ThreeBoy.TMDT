<?php
/**
 * @class   WS247_aio_ct_button_Theme
 */
 
if( !class_exists('WS247_aio_ct_button_Theme') ):
	class WS247_aio_ct_button_Theme{
		/**
		 * Constructor
		 */
		function __construct() {
			$this->init();
		}
		
		public function init(){
			add_action('wp_enqueue_scripts', array($this, 'register_scripts') );
			add_action('wp_footer', array($this, 'wle_contact_icons_display'), 99999, 0);
		}

		function register_scripts() {
			//Css
			wp_enqueue_style( WS247_AIO_CT_BUTTON_PREFIX.'aio_ct_button.css', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/aio_ct_button.css', false, WS247_AIO_CT_BUTTON_VER );
			
			//fontawesome-free-6.6.0
			wp_enqueue_style( WS247_AIO_CT_BUTTON_PREFIX.'fontawesome-6.6.0', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/js/fontawesome-free-6.6.0/css/all.min.css', false, '6.6.0' );

			wp_enqueue_script( WS247_AIO_CT_BUTTON_PREFIX.'aio_ct_button', WS247_AIO_CT_BUTTON_PLUGIN_INC_ASSETS_URL . '/aio_ct_button.js', array('jquery'), WS247_AIO_CT_BUTTON_VER, true );
			
		}
		
		static function wle_contact_icons_display(){
			$arr_field = Ws247_aio_ct_button::get_arr_icons(); 

			$shake_hotline = Ws247_aio_ct_button::class_get_option('shake_hotline');
			$hide_shake_hotline = Ws247_aio_ct_button::class_get_option('hide_shake_hotline');
			$shake_hotline_pos = Ws247_aio_ct_button::class_get_option('shake_hotline_pos'); 
			$icons_pos = Ws247_aio_ct_button::class_get_option('icons_pos'); 
			
			$primary_color = Ws247_aio_ct_button::class_get_option('primary_color');
			$hover_color = Ws247_aio_ct_button::class_get_option('hover_color'); 
			$text_color = Ws247_aio_ct_button::class_get_option('text_color');   
			$text_contact_color = Ws247_aio_ct_button::class_get_option('text_contact_color'); 
			
			$text_contact = Ws247_aio_ct_button::class_get_option('text_contact'); 
			if(!$text_contact){ $text_contact = __('Contact', WS247_AIO_CT_BUTTON_TEXTDOMAIN); } 

			$text_contact_bottom = Ws247_aio_ct_button::class_get_option('text_contact_bottom'); 
			
			$shake_hotline_bottom = (int)Ws247_aio_ct_button::class_get_option('shake_hotline_bottom');
			$icons_bottom = (int)Ws247_aio_ct_button::class_get_option('icons_bottom'); 
			$talkto_embed = Ws247_aio_ct_button::class_get_option('talkto_embed');
			$zalo_oa_id = Ws247_aio_ct_button::class_get_option('zalo_oa_id');
			$hide_icons = Ws247_aio_ct_button::class_get_option('hide_icons'); 
			$zalo_ring = Ws247_aio_ct_button::class_get_option('is_zalo_shake_hotline'); 
			$hide_text_icons = Ws247_aio_ct_button::class_get_option('hide_text_icons'); 
			$container_style = Ws247_aio_ct_button::class_get_option('container_style');
			$ft_color_1 = Ws247_aio_ct_button::class_get_option('ft_color_1');
			$ft_color_2 = Ws247_aio_ct_button::class_get_option('ft_color_2');
			$icons_verticle = Ws247_aio_ct_button::class_get_option('icons_verticle');
			$is_hide_first = Ws247_aio_ct_button::class_get_option('is_hide_first');
			$icon_text_on_left = Ws247_aio_ct_button::class_get_option('icon_text_on_left');
			$icons_animation = Ws247_aio_ct_button::class_get_option('icons_animation');
			$hide_hotline_number_only = Ws247_aio_ct_button::class_get_option('hide_hotline_number_only');

			if($shake_hotline && $hide_shake_hotline != 'on'){

				$shake_hotline_t = 'tel:'.esc_attr($shake_hotline);

				if($zalo_ring){
					$shake_hotline_t = esc_attr($shake_hotline);

					if(strpos($shake_hotline_t, 'https://zalo.me') === false){
						$shake_hotline_t = 'https://zalo.me/'.esc_attr($shake_hotline);
					}
					
					$shake_hotline = 'Zalo';
				}
			?>
				<div id="ws247-aio-ct-button-hl" class="hotline <?php if($shake_hotline_pos==2){ echo 'hotline-on-right'; } ?>">
					<div id="phonering-alo-phoneIcon" class="phonering-alo-phone phonering-alo-green phonering-alo-show">
                    	<span class="number">
                    		<?php 
                    		if(!$hide_hotline_number_only){
                    		?>
                    		<a href="<?php echo $shake_hotline_t; ?>"><i class="fas fa-caret-left"></i><?php echo esc_attr($shake_hotline); ?></a>
                    		<?php } ?>
                    	</span>
						<div class="phonering-alo-ph-circle"></div>
						<div class="phonering-alo-ph-circle-fill"></div>
						<div class="phonering-alo-ph-img-circle <?php if($zalo_ring){ ?>zalo-ring<?php } ?>">
							<a class="pps-btn-img" href="<?php echo $shake_hotline_t; ?>"></a>
						</div>
					</div>
				</div>
			<?php
			}
			?>
            
            <style>
				<?php 
				if($primary_color):
				?>
				.phonering-alo-phone.phonering-alo-hover .phonering-alo-ph-img-circle, .phonering-alo-phone:hover .phonering-alo-ph-img-circle,
            	.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-img-circle, #phonering-alo-phoneIcon .number a,
				#phonering-alo-phoneIcon .number a, #ft-contact-icons .item span.ab {
					background-color: <?php echo esc_attr($primary_color); ?>;
				}
				.phonering-alo-phone.phonering-alo-hover .phonering-alo-ph-circle, .phonering-alo-phone:hover .phonering-alo-ph-circle,
				.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-circle {
					border-color: <?php echo esc_attr($primary_color); ?>;
				}
				#phonering-alo-phoneIcon .number i, #ft-contact-icons .item span.ab i{
					color: <?php echo esc_attr($primary_color); ?>;
				}
				<?php 
				endif;
				?>
				
				<?php 
				if($hover_color):
				?>
				#ft-contact-icons .item a:hover span.ab,.phonering-alo-phone.phonering-alo-green.phonering-alo-hover .phonering-alo-ph-img-circle, .phonering-alo-phone.phonering-alo-green:hover .phonering-alo-ph-img-circle, #phonering-alo-phoneIcon:hover .number a{
					background-color:<?php echo esc_attr($hover_color);?>;
				}
				.phonering-alo-phone.phonering-alo-green.phonering-alo-hover .phonering-alo-ph-circle, 
				.phonering-alo-phone.phonering-alo-green:hover .phonering-alo-ph-circle, #phonering-alo-phoneIcon:hover .number a{
					border-color: <?php echo esc_attr($hover_color);?>;
				}
				#phonering-alo-phoneIcon:hover .number i, #ft-contact-icons .item a:hover span.ab i{
					color: <?php echo esc_attr($hover_color);?>;
				}
				<?php 
				endif;
				?>
				
				<?php 
				if($text_color):
				?>
					#ft-contact-icons li span.ab label, #ft-contact-icons .item span.ab label
, #phonering-alo-phoneIcon .number a{
						color:<?php echo esc_attr($text_color); ?> !important;
					}
				<?php 
				endif;
				?>
				
				<?php 
				if($shake_hotline_bottom):
				?>
					.phonering-alo-phone{
						bottom:<?php echo esc_attr($shake_hotline_bottom);?>px;
					}
				<?php 
				endif;
				?>
				
				<?php 
				if($icons_bottom):
				?>
					#ft-contact-icons{
						bottom:<?php echo esc_attr($icons_bottom); ?>px;
					}
				<?php 
				endif;
				?>

				<?php 
				if($text_contact_bottom):
				?>
					.show-all-icon{
						bottom:<?php echo esc_attr($text_contact_bottom); ?>px;
					}
				<?php 
				endif;
				?>

				
				
				<?php 
				if($text_contact_color):
				?>
					.show-all-icon, .show-all-icon i{
						color:<?php echo esc_attr($text_contact_color); ?>;
					}
				<?php 
				endif;
				?>

				<?php do_action('ws247_aio_style'); ?>
            </style>
            
            <?php 
            $custom_class = 'ws247-aio-container-wpshare247';
            $custom_class = apply_filters( 'ws247_aio_container_class', $custom_class );
            ?>

			<div id="ws247-aio-ct-button-show-all-container" class="<?php if($icons_verticle) echo 'aio-fixed-bt-mb'; ?> <?php echo $custom_class; ?>">

			<?php 
			$hide_def = ''; $active_def = '';
			if($hide_icons != 'on'){
				if(!$is_hide_first){
					$hide_def = 'hide-me';
					$active_def = 'active';
				}

				if($container_style) { 
					
					if($container_style=='ft-pn-sn' && $ft_color_1 && $ft_color_2){
						?>
						<style type="text/css">
							#ft-contact-icons-out-m.ft-pn-sn{
							    background-image: linear-gradient(120deg, <?php echo $ft_color_1; ?> 0%, <?php echo $ft_color_2; ?> 100%);
							}
						</style>
						<?php
					}

					$container_style .= ' aio-has-border'; 
				}
			?>
            <a id="ws247-aio-ct-button-show-all-icon" href="#" class="<?php echo $hide_def;?> js-show-all-icon show-all-icon <?php if($icons_pos!=2) echo 'contact-icons-right'; ?>"><span><?php echo esc_attr($text_contact);?></span><i class="fas fa-long-arrow-alt-up"></i></a>

            <?php 
	            $hide_text_clss = '';
				if($hide_text_icons){
					$hide_text_clss = 'text-is-hide';
				}
            ?>

			<div id="ft-contact-icons" class="<?php echo esc_attr($hide_text_clss);?> <?php echo $active_def;?> <?php if($icons_pos!=2) echo 'contact-icons-right'; ?>">
				<div id="ft-contact-icons-out-m" class="<?php echo $container_style; ?> <?php echo $icons_animation; ?> <?php if($icon_text_on_left) echo 'ft-icon-left'; ?>">
					
					<?php 
					if($arr_field){ 
						$arr_ = apply_filters( 'ws247_aio_ct_icons_arr_show', $arr_field ); 
						$s_tr_html = '';
						ob_start();
						foreach ($arr_ as $field_name => $arr_item) {
							$is_custom_f = isset($arr_item['custom']) ? $arr_item['custom'] : 0; 
							if($is_custom_f){
								$link = $arr_item['link'];
				        		$text = $arr_item['txt'];
				        		$hide = $arr_item['hide'];

				        		$item_class = '';
				        		$item_font = $arr_item['font_i'];
							}else{
				        		$link = Ws247_aio_ct_button::class_get_option($field_name);
					        	$text = Ws247_aio_ct_button::class_get_option('text_'.$field_name);
					        	$hide = Ws247_aio_ct_button::class_get_option('hide_'.$field_name);

					        	$item_class = isset($arr_item[4]) ? $arr_item[4] : '';
					        	$item_font = isset($arr_item[5]) ? $arr_item[5] : '';
							}

							if($link && $hide != 'on'):
								if($field_name=='stt_hotline'){
									$link = 'tel:'.$link;
								}
								if($field_name=='company_zalo' && strpos($link, 'zalo.me') === false){
									$link = 'https://zalo.me/'.$link;
								}
								if($field_name=='stt_email'){
									$link = 'mailto:'.$link;
								}
								if($field_name=='icon_google_map'){
									$link = urldecode('https://maps.google.com/?q='. $link);
								}
							?>
							<div id="<?php echo esc_attr($field_name); ?>" class="<?php echo esc_attr($item_class);?> item aio-ct-icon-new">
								<a target="_blank" href="<?php echo esc_attr($link); ?>">
			                    	<span class="icon"><?php echo ($item_font);?></span>
			                        <?php 
									if($text){
									?>
			                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text); ?></label></span>
			                        <?php 
									}
									?>
			                    </a>
							</div>
							<?php
							endif;
						}
						$s_tr_html = ob_get_contents();
						ob_end_clean();

						$s_tr_html = apply_filters( 'ws247_aio_ct_icons_html', $s_tr_html );
						echo $s_tr_html;
					}
					?>

					<?php do_action( 'ws247_aio_custom_items' ); ?>
				</div>
                
                <div class="item"><a href="#" id="js-hide-all-icon-e" class="js-hide-all-icon"><span class="icon"><i class="fas fa-times"></i></span></a></div>
			</div>
            
            <?php 
			if($talkto_embed){
				echo $talkto_embed;
			}

			if($zalo_oa_id){
				?>
				<div class="zalo-chat-widget" data-oaid="<?php echo $zalo_oa_id; ?>" data-welcome-message="Bạn cần tư vấn?" data-autopopup="0" data-width="" data-height=""></div><script src="https://sp.zalo.me/plugins/sdk.js"></script>
				<?php
			}
			?>
            </div>

			<?php
			}
		}

	
	//End class------------------------
	}
	
	//Init
	$WS247_aio_ct_button_Theme = new WS247_aio_ct_button_Theme();
	
endif;
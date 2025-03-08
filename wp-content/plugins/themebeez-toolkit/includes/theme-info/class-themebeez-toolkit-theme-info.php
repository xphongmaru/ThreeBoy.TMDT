<?php
/**
 * The file that defines the class of theme info
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Toolkit
 * @subpackage Themebeez_Toolkit/includes
 */

if ( ! class_exists( 'Themebeez_Toolkit_Theme_Info' ) ) {

	/**
	 * Class - Themebeez_Toolkit_Theme_Info.
	 *
	 * @since 1.0.0
	 */
	class Themebeez_Toolkit_Theme_Info {

		/**
		 * Class version.
		 *
		 * @var string $version Version.
		 */
		private $version = '1.1.5';

		/**
		 * Page configuration.
		 *
		 * @var array $config Configuration.
		 */
		private $config;

		/**
		 * Theme name.
		 *
		 * @var string $theme_name Theme name.
		 */
		private $theme_name;

		/**
		 * Logo image URL.
		 *
		 * @var string $logo_url Logo image URL.
		 */
		private $logo_url;

		/**
		 * Logo link.
		 *
		 * @var string $logo_link Logo link.
		 */
		private $logo_link;

		/**
		 * Theme slug.
		 *
		 * @var string $theme_slug Theme slug.
		 */
		private $theme_slug;

		/**
		 * Theme textdomain.
		 *
		 * @var string $theme_textdomain Theme TextDomain.
		 */
		private $theme_textdomain;

		/**
		 * Current theme object.
		 *
		 * @var WP_Theme $theme Current theme.
		 */
		private $theme;

		/**
		 * Theme version.
		 *
		 * @var string $theme_version Theme version.
		 */
		private $theme_version;

		/**
		 * Admin menu name.
		 *
		 * @var string $menu_name Menu name under Appearance.
		 */
		private $menu_name;

		/**
		 * Page title.
		 *
		 * @var string $page_name Title of the about page.
		 */
		private $page_name;

		/**
		 * Page slug.
		 *
		 * @var string $page_slug Slug of about page.
		 */
		private $page_slug;

		/**
		 * Recommended action option key.
		 *
		 * @var string $action_option_key Action key.
		 */
		private $action_key;

		/**
		 * Page tabs.
		 *
		 * @var array $tabs Page tabs.
		 */
		private $tabs;

		/**
		 * HTML notification content displayed upon activation.
		 *
		 * @var string $notification HTML notification content.
		 */
		private $notification;

		/**
		 * Singleton instance of Themebeez_Toolkit_Theme_Info.
		 *
		 * @var Themebeez_Toolkit_Theme_Info $instance Themebeez_Toolkit_Theme_Info instance.
		 */
		private static $instance;

		/**
		 * Main Themebeez_Toolkit_Theme_Info instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $config Configuration array.
		 */
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Themebeez_Toolkit_Theme_Info ) ) {
				self::$instance = new Themebeez_Toolkit_Theme_Info();
				if ( ! empty( $config ) && is_array( $config ) ) {
					self::$instance->config = $config;
					self::$instance->setup_config();
					self::$instance->setup_actions();
				}
			}
		}

		/**
		 * Setup the class props based on the config array.
		 *
		 * @since 1.0.0
		 */
		public function setup_config() {

			$theme = wp_get_theme();

			$this->theme_name       = $theme->get( 'Name' );
			$this->theme_version    = $theme->get( 'Version' );
			$this->theme_slug       = $theme->get_template();
			$this->theme_textdomain = $theme->get( 'TextDomain' );
			$this->page_slug        = $this->theme_textdomain . '-about';
			$this->action_key       = $this->theme_textdomain . '-recommended_actions';
			$this->menu_name        = isset( $this->config['menu_name'] ) ? $this->config['menu_name'] : $this->theme_name;
			$this->page_name        = isset( $this->config['page_name'] ) ? $this->config['page_name'] : $this->theme_name;
			$this->logo_url         = isset( $this->config['logo_url'] ) ? $this->config['logo_url'] : plugin_dir_url( __FILE__ ) . 'images/themebeez.png';
			$this->logo_link        = isset( $this->config['logo_link'] ) ? $this->config['logo_link'] : 'https://www.themebeez.com/';
			$this->tabs             = isset( $this->config['tabs'] ) ? $this->config['tabs'] : array();

			$themepage_url = admin_url( 'themes.php?page=' . $this->page_slug );

			$this->notification = isset( $this->config['notification'] ) ? $this->config['notification'] : ( '<p>' . sprintf(
				/* translators: 1: theme name, 2: theme page anchor opening tag, 3: anchor closing tag */
				esc_html__( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$swelcome page%3$s.', 'themebeez-toolkit' ),
				$this->theme_name,
				'<a href="' . esc_url( $themepage_url ) . '">',
				'</a>'
			) . '</p><p><a href="' . esc_url( $themepage_url ) . '" class="button button-primary" style="text-decoration: none;">' . sprintf(
				/* translators: 1: theme name */
				esc_html__( 'Get started with %s', 'themebeez-toolkit' ),
				$this->theme_name
			) . '</a></p>' ); // phpcs:ignore
		}

		/**
		 * Setup actions.
		 *
		 * @since 1.0.0
		 */
		public function setup_actions() {

			add_action( 'admin_menu', array( $this, 'register' ) );
			add_action( 'load-themes.php', array( $this, 'activation_admin_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
			add_action( 'admin_head', array( $this, 'admin_style' ) );
			add_action( 'wp_ajax_tt_about_action_dismiss_recommended_action', array( $this, 'dismiss_recommended_action_callback' ) );
			add_action( 'wp_ajax_nopriv_tt_about_action_dismiss_recommended_action', array( $this, 'dismiss_recommended_action_callback' ) );
		}

		/**
		 * Register the page under Appearance.
		 *
		 * @since 1.0.0
		 */
		public function register() {

			add_menu_page(
				$this->theme_name,
				$this->theme_name,
				'manage_options',
				$this->theme_slug,
				array( $this, 'render_about_page' ),
				'dashicons-art',
				30
			);

			add_submenu_page(
				$this->theme_slug,
				esc_html__( 'Theme Dashboard', 'themebeez-toolkit' ),
				esc_html__( 'Theme Dashboard', 'themebeez-toolkit' ),
				'manage_options',
				$this->theme_slug,
				array( $this, 'render_about_page' ),
			);

			add_submenu_page(
				$this->theme_slug,
				esc_html__( 'Starter Templates', 'themebeez-toolkit' ),
				esc_html__( 'Starter Templates', 'themebeez-toolkit' ),
				'manage_options',
				$this->theme_slug . '&tab=starter_templates',
				array( $this, 'render_about_page' ),
			);

			add_submenu_page(
				$this->theme_slug,
				esc_html__( 'Plugins', 'themebeez-toolkit' ),
				esc_html__( 'Plugins', 'themebeez-toolkit' ),
				'manage_options',
				$this->theme_slug . '&tab=plugins',
				array( $this, 'render_about_page' ),
			);
		}

		/**
		 * Get total recommended actions count.
		 *
		 * @since 1.0.0
		 *
		 * @return int Total count.
		 */
		private function get_total_recommended_actions() {

			$actions = $this->get_recommended_actions();
			return count( $actions );
		}

		/**
		 * Return valid array of recommended actions.
		 *
		 * @return array Valid array of recommended actions.
		 */
		private function get_recommended_actions() {

			$saved_actions = get_option( $this->action_key );

			if ( ! is_array( $saved_actions ) ) {
				$saved_actions = array();
			}

			$valid = array();

			$action_config = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();

			if ( ! empty( $action_config['content'] ) ) {

				foreach ( $action_config['content'] as $item ) {
					if ( isset( $item['check'] ) && true === $item['check'] ) {
						continue;
					}
					if ( isset( $saved_actions[ $item['id'] ] ) && false === $saved_actions[ $item['id'] ] ) {
						continue;
					}
					$valid[] = $item;
				}
			}

			return $valid;
		}

		/**
		 * Render quick links.
		 *
		 * @since 1.0.0
		 */
		public function render_quick_links() {

			$quick_links = ( isset( $this->config['quick_links'] ) ) ? $this->config['quick_links'] : array();

			if ( empty( $quick_links ) ) {
				return;
			}

			echo '<div class="tt-theme-quick-links-wrapper">';

			foreach ( $quick_links as $link ) {
				?>
				<div class="tt-theme-quick-link">
					<?php
					if ( isset( $link['title'] ) && ! empty( $link['title'] ) ) {
						$title = $link['title'];
						if ( isset( $link['icon'] ) && ! empty( $link['icon'] ) ) {
							?>
							<h3 class="quick-link-title"><span class="quick-link-icon dashicons <?php echo esc_attr( $link['icon'] ); ?>"></span> <?php echo esc_html( $link['title'] ); ?></h3>
							<?php
						} else {
							?>
							<h3 class="quick-link-title"><?php echo esc_html( $link['title'] ); ?></h3>
							<?php
						}
						?>
						<?php
					}

					if ( isset( $link['desc'] ) && ! empty( $link['desc'] ) ) {
						?>
						<p class="quick-link-desc"><?php echo esc_html( $link['desc'] ); ?></p>
						<?php
					}

					if (
						isset( $link['link_title'] ) && ! empty( $link['link_title'] ) &&
						isset( $link['link_url'] ) && ! empty( $link['link_url'] )
					) {
						$button_class = isset( $link['link_class'] ) ? $link['link_class'] : '';
						?>
						<p class="quick-link-url">
							<a class="<?php echo esc_attr( $button_class ); ?>" href="<?php echo esc_url( $link['link_url'] ); ?>" target="_blank"><?php echo esc_html( $link['link_title'] ); ?></a>
						</p>
						<?php
					}
					?>
				</div>
				<?php
			}

			echo '</div>';
		}

		/**
		 * Renders theme dashboard header.
		 *
		 * @since 1.2.7
		 */
		public function render_page_header() {

			$sale_plan     = isset( $this->config['sale_plan'] ) ? $this->config['sale_plan'] : '';
			$changelog_url = isset( $this->config['changelog_url'] ) ? $this->config['changelog_url'] : '';
			$theme_url     = isset( $this->config['theme_url'] ) ? $this->config['theme_url'] : '';
			?>
			<div id="tt-page-header">
				<div id="tt-author-branding">
					<h2 id="tt-author-title">
						<a href="<?php echo esc_url( $this->logo_link ); ?>">
							<img id="tt-author-logo" src="<?php echo esc_url( $this->logo_url ); ?>" alt="<?php echo esc_attr__( 'Themebeez Logo', 'themebeez-toolkit' ); ?>">
							<span><?php echo esc_html__( 'Themebeez', 'themebeez-toolkit' ); ?></span>
						</a>
					</h2>
				</div>
				<div id="tt-theme-info">
					<p id="tt-theme-version-changelog-type-link">
						<span id="tt-theme-version"><?php echo esc_html( $this->theme_version ); ?> <span id="tt-theme-type"><?php echo esc_html( $sale_plan ); ?></span></span>
						
						<a id="tt-changelog-link" href="<?php echo esc_url( $changelog_url ); ?>" target="_blank" title="<?php echo esc_attr__( 'Changelog link', 'themebeez-toolkit' ); ?>">
							<span class="tt-theme-info-icons dashicons dashicons-bell"></span>
						</a>

						<a id="tt-theme-link" href="<?php echo esc_url( $theme_url ); ?>" target="_blank">Website <span class="tt-theme-info-icons dashicons dashicons-external"></span></a>
					</p>
				</div>
			</div>
			<?php
		}

		/**
		 * Render welcome section.
		 *
		 * @since 1.2.7
		 */
		public function render_welcome_section() {

			$current_user = wp_get_current_user();

			// Greeting Content.
			$greeting = esc_html__( 'Hello! 👋', 'themebeez-toolkit' );

			if ( $current_user instanceof WP_User ) {
				$greeting = sprintf(
					/* translators: 1: username. */
					esc_html__( 'Hello, %s 👋', 'themebeez-toolkit' ),
					$current_user->display_name
				);
			}

			// Title content.
			$welcome_title = sprintf(
				/* translators: 1: theme name. */
				esc_html__( 'Welcome to %s', 'themebeez-toolkit' ),
				$this->theme_name
			);

			$welcome_subtitle = sprintf(
				/* translators: 1: theme name. */
				esc_html__( 'Thank you for choosing %s. Let&rsquo;s get your site up and running quickly.', 'themebeez-toolkit' ),
				$this->theme_name
			);
			?>
			<div class="tt-welcome-wrapper">
				<h3 class="tt-welcome-greeting"><?php echo esc_html( $greeting ); ?>'</h3>
				<h2 class="tt-welcome-heading"><?php echo esc_html( $welcome_title ); ?></h2>
				<p class="tt-welcome-subheading"><?php echo esc_html( $welcome_subtitle ); ?></p>
				<p class="tt-welcome-actions">
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button tt-button button-primary">
						<?php echo esc_html__( 'Start Customizing', 'themebeez-toolkit' ); ?>
					</a>
				</p>
			</div>
			<?php
		}

		/**
		 * Render main page.
		 *
		 * @since 1.0.0
		 */
		public function render_about_page() {

			echo '<div class="tt-dashboard-page">';

			$this->render_page_header();

			if ( ! empty( $this->tabs ) ) {

				echo '<div class="wrap about-wrap pt-wrap">';

				echo '<div class="tt-main-content-wrapper">';

				// Display tabs.
				if ( ! empty( $this->tabs ) ) {

					echo '<div class="tt-main-content-inner">';

					$this->render_welcome_section();

					echo '<div class="tt-nav-tabs-content-wrapper">';

					$active_tab = isset( $_GET['tab'] ) ? wp_unslash( $_GET['tab'] ) : 'getting_started'; // phpcs:ignore

					echo '<div class="tt-nav-tabs-wrapper">';

					foreach ( $this->tabs as $tab_key => $tab_name ) {

						if ( 'useful_plugins' === $tab_key ) {
							global $tgmpa;
							if ( ! isset( $tgmpa ) ) {
								continue;
							}
						}

						echo '<a href="' . esc_url( admin_url( 'admin.php?page=' . $this->theme_slug ) ) . '&tab=' . esc_attr( $tab_key ) . '" class="tt-nav-tab ' . ( $active_tab === $tab_key ? 'active' : '' ) . '" role="tab" data-toggle="tab">';

						if ( 'upgrade_to_pro' === $tab_key ) {
							echo '<span class="dashicons dashicons-star-filled"></span>';
						}

						echo esc_html( $tab_name );

						if ( 'recommended_actions' === $tab_key ) {
							$count = $this->get_total_recommended_actions();
							if ( $count > 0 ) {
								echo '<span class="badge-action-count">' . esc_html( $count ) . '</span>';
							}
						}

						echo '</a>';
					}

					echo '</div><!-- .tt-nav-tabs-wrapper -->';

					// Display content for current tab.
					if ( method_exists( $this, $active_tab ) ) {
						$this->$active_tab();
					}

					echo '</div><!-- .tt-nav-tabs-content-wrapper -->';

					echo '</div>';
				}

				$this->render_quick_links();

				echo '</div>';

				echo '</div><!--/.wrap.about-wrap-->';
			}

			echo '</div><!-- .tt-dashboard-page -->';
		}

		/**
		 * Adds an admin notice upon successful activation.
		 *
		 * @since 1.0.0
		 */
		public function activation_admin_notice() {

			global $pagenow;

			if ( is_admin() && ( 'themes.php' === $pagenow ) && isset( $_GET['activated'] ) ) { // phpcs:ignore
				add_action( 'admin_notices', array( $this, 'welcome_admin_notice' ), 99 );
			}
		}

		/**
		 * Display an admin notice linking to the about page.
		 *
		 * @since 1.0.0
		 */
		public function welcome_admin_notice() {

			if ( ! empty( $this->notification ) ) {

				echo '<div class="updated notice is-dismissible">';
				echo wp_kses_post( $this->notification );
				echo '</div>';
			}
		}

		/**
		 * Load assets.
		 *
		 * @since 1.0.0
		 */
		public function load_assets() {

			global $pagenow;

			if (
				'admin.php' === $pagenow &&
				( isset( $_GET['page'] ) && $this->theme_slug === $_GET['page'] ) // phpcs:ignore
			) {

				wp_enqueue_style( 'plugin-install' );
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'updates' );

				wp_enqueue_style(
					'themebeez-toolkit-theme-info',
					plugin_dir_url( __FILE__ ) . 'css/theme-info.css',
					array(),
					THEMEBEEZTOOLKIT_VERSION,
					'all'
				);

				wp_enqueue_script(
					'themebeez-toolkit-theme-info',
					plugin_dir_url( __FILE__ ) . 'js/theme-info.js',
					array( 'jquery' ),
					THEMEBEEZTOOLKIT_VERSION,
					true
				);

				$js_vars = array(
					'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				);

				wp_localize_script( 'themebeez-toolkit-about', 'ttkitAboutObject', $js_vars );
			}
		}

		/**
		 * Embedded admin styles.
		 *
		 * @since 1.0.0
		 */
		public function admin_style() {
			?>
			<style type="text/css">
				.badge-action-count {
					padding: 0 6px;
					display: inline-block;
					background-color: #d54e21;
					color: #fff;
					font-size: 9px;
					line-height: 17px;
					font-weight: 600;
					margin: 1px 0 0 2px;
					vertical-align: top;
					border-radius: 10px;
					z-index: 26;
					margin-top: 5px;
					margin-left: 5px;
				}
				.wp-submenu .badge-action-count {
					margin-top: 0;
				}
			</style>
			<?php
		}

		/**
		 * Render getting started tab.
		 *
		 * @since 1.0.0
		 */
		public function getting_started() {

			echo '<div class="tt-theme-features-section">';

			if ( ! empty( $this->config['getting_started'] ) ) {

				$getting_started = $this->config['getting_started'];

				if ( ! empty( $getting_started ) ) {

					foreach ( $getting_started as $key => $features ) {

						$title = ( 'free' === $key ) ?
						esc_html__( 'Quick Settings', 'themebeez-toolkit' ) :
						esc_html__( 'Premium Features', 'themebeez-toolkit' );

						$pro_url = isset( $this->config['pro_version']['url'] ) ? $this->config['pro_version']['url'] : '';

						$link_url   = ( 'free' === $key ) ? admin_url( 'customize.php' ) : $pro_url;
						$link_title = ( 'free' === $key ) ? esc_html__( 'Go To Customizer', 'themebeez-toolkit' ) : esc_html__( 'Upgrade Now', 'themebeez-toolkit' );
						?>
						<div class="tt-features-section-inner">
							<div class="tt-features-heading">
								<h3><?php echo esc_html( $title ); ?></h3>
								<a href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
							</div>
							<div class="tt-features-wrapper">
								<?php
								foreach ( $features as $feature ) {
									?>
									<div class="feature-content">
										<?php
										if ( ! empty( $feature['title'] ) ) {
											echo '<h3 class="feature-title">' . esc_html( $feature['title'] ) . '</h3>';
										}

										if ( ! empty( $feature['button_link'] ) && ! empty( $feature['button_label'] ) ) {

											echo '<span class="feature-link-wrapper">';

											$button_class = 'feature-link';
											if ( $feature['is_button'] ) {
												$button_class = 'button tt-button button-primary';
											}

											$count = $this->get_total_recommended_actions();

											if ( $feature['recommended_actions'] && isset( $count ) ) {
												if ( 0 === $count ) {
													echo '<span class="dashicons dashicons-yes"></span>';
												} else {
													echo '<span class="dashicons dashicons-no-alt"></span>';
												}
											}

											$button_new_tab = '_self';
											if ( isset( $feature['is_new_tab'] ) ) {
												if ( $feature['is_new_tab'] ) {
													$button_new_tab = '_blank';
												}
											}

											$dashicon_class = ( 'free' === $key ) ? 'dashicons-admin-settings' : 'dashicons-external';

											echo '<span class="feature-link-icon dashicons ' . esc_attr( $dashicon_class ) . '"></span> <a target="' . esc_attr( $button_new_tab ) . '" href="' . esc_url( $feature['button_link'] ) . '"class="' . esc_attr( $button_class ) . '">' . esc_html( $feature['button_label'] ) . '</a>';
											echo '</span>';
										}

										if ( 'pro' === $key ) {
											echo '<span class="pro-badge">' . esc_html__( 'Pro', 'themebeez-toolkit' ) . '</span>';
										}
										?>
									</div>
									<?php
								}
								?>
							</div>
						</div>
						<?php
					}
				}
			}

			echo '</div><!-- .tt-theme-features-section -->';
		}

		/**
		 * Render starter templates tab content.
		 *
		 * @since 1.2.7
		 */
		public function starter_templates() {

			do_action( 'themebeez_toolkit_starter_templates' );
		}

		/**
		 * Render plugins tab.
		 *
		 * @since 1.0.0
		 */
		public function plugins() {

			$plugins = get_transient( 'tt_plugins' );

			if ( ! is_array( $plugins ) ) {

				$authors     = array( 'themebeez', 'addonify' );
				$all_plugins = array();

				foreach ( $authors as $author ) {
					$url      = "https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&author=$author";
					$response = wp_remote_get( $url );

					if ( ! is_wp_error( $response ) ) {
						$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
						if ( ! empty( $response_body['plugins'] ) ) {
							$all_plugins = array_merge( $all_plugins, $response_body['plugins'] );
						}
					}
				}

				if ( empty( $all_plugins ) ) {
					return;
				}

				$unique_plugin_slugs = array();
				$unique_plugins      = array();

				foreach ( $all_plugins as $plugin ) {

					if ( 'themebeez-toolkit' === $plugin['slug'] ) {
						continue;
					}

					if ( ! in_array( $plugin['slug'], $unique_plugin_slugs, true ) ) {
						$unique_plugin_slugs[] = $plugin['slug'];
						$unique_plugins[]      = $plugin;
					}
				}

				set_transient( 'tt_plugins', $unique_plugins, 86400 );

				$plugins = $unique_plugins;
			}
			?>
			<div class="tt-plugins-tab-content">
				<div class="tt-plugins-wrapper">
					<?php
					foreach ( $plugins as $plugin ) {
						?>
						<div class="tt-plugin-content">
							<div class="tt-plugin-content-inner">
								<div class="plugin-image">
									<a href="<?php echo esc_url( $plugin['homepage'] ); ?>" target="_blank">
										<img src="<?php echo esc_url( $plugin['icons']['2x'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ); ?>" width="200px" height="auto">
									</a>
								</div>
								<div class="plugin-detail">
									<h3 class="plugin-name">
										<a href="<?php echo esc_url( $plugin['homepage'] ); ?>" target="_blank">
											<?php echo esc_html( $plugin['name'] ); ?>
										</a>
									</h3>
									<p class="plugin-short-desc"><?php echo esc_html( $plugin['short_description'] ); ?></p>
									<?php
									$active = $this->check_if_plugin_active( $plugin['slug'] );
									$url    = $this->create_action_link( $active['needs'], $plugin['slug'] );

									$label = '';

									switch ( $active['needs'] ) {

										case 'install':
											$class = 'install-now button tt-button';
											$label = esc_html__( 'Install Now', 'themebeez-toolkit' );
											break;
										case 'activate':
											$class = 'activate-now button tt-button button-primary';
											$label = esc_html__( 'Activate Now', 'themebeez-toolkit' );
											break;
										case 'deactivate':
											$class = 'deactivate-now button tt-button';
											$label = esc_html__( 'Deactivate', 'themebeez-toolkit' );
											break;
									}
									?>
									<p class="plugin-card-<?php echo esc_attr( $plugin['slug'] ); ?> action_button <?php echo ( 'install' !== $active['needs'] && $active['status'] ) ? 'active' : ''; ?>">
										<a data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
									</p>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Render themes tab.
		 *
		 * @since 1.0.0
		 */
		public function themes() {

			$authors    = array( 'themebeez' );
			$all_themes = array();

			foreach ( $authors as $author ) {
				$url      = "https://api.wordpress.org/themes/info/1.2/?action=query_themes&author=$author";
				$response = wp_remote_get( $url );

				if ( ! is_wp_error( $response ) ) {
					$themes = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( ! empty( $themes['themes'] ) ) {
						$all_themes = array_merge( $all_themes, $themes['themes'] );
					}
				}
			}

			if ( empty( $all_themes ) ) {
				return;
			}

			$unique_theme_slugs = array();
			$unique_themes      = array();

			foreach ( $all_themes as $theme ) {
				if ( ! in_array( $theme['slug'], $unique_theme_slugs, true ) ) {
					$unique_theme_slugs[] = $theme['slug'];
					$unique_themes[]      = $theme;
				}
			}
			?>
			<div class="tt-plugins-tab-content">
				<div class="tt-plugins-wrapper">
					<?php
					$skip_themes = array();
					foreach ( $unique_themes as $theme ) {
						if ( in_array( $theme['slug'], $skip_themes, true ) ) {
							continue;
						}
						?>
						<div class="tt-plugin-content">
							<div class="tt-plugin-content-inner">
								<div class="plugin-image">
									<a href="<?php echo esc_url( $theme['homepage'] ); ?>" target="_blank">
										<img src="<?php echo esc_url( $theme['screenshot_url'] ); ?>" alt="<?php echo esc_attr( $theme['name'] ); ?>">
									</a>
								</div>
								<div class="plugin-detail">
									<h3 class="plugin-name">
										<a href="<?php echo esc_url( $theme['homepage'] ); ?>" target="_blank">
											<?php echo esc_html( $theme['name'] ); ?>
										</a>
									</h3>
									<p class="plugin-short-desc"><?php echo esc_html( $theme['description'] ); ?></p>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Render upgrade tab.
		 *
		 * @since 1.0.0
		 */
		public function upgrade_to_pro() {

			$upgrade_to_pro = ( isset( $this->config['upgrade_to_pro'] ) ) ? $this->config['upgrade_to_pro'] : array();

			echo '<div class="feature-section upgrade-to-pro">';

			if ( isset( $upgrade_to_pro['description'] ) && ! empty( $upgrade_to_pro['description'] ) ) {
				echo '<div>' . wp_kses_post( $upgrade_to_pro['description'] ) . '</div>';
			}

			if ( isset( $upgrade_to_pro['button_link'] ) && ! empty( $upgrade_to_pro['button_link'] ) ) {
				$button_text = esc_html__( 'Upgrade to Pro', 'themebeez-toolkit' );

				if ( isset( $upgrade_to_pro['button_label'] ) && ! empty( $upgrade_to_pro['button_label'] ) ) {
					$button_text = $upgrade_to_pro['button_label'];
				}

				$target = '_self';
				if ( isset( $upgrade_to_pro['is_new_tab'] ) && true === $upgrade_to_pro['is_new_tab'] ) {
					$target = '_blank';
				}

				echo '<a href="' . esc_url( $upgrade_to_pro['button_link'] ) . '" class="button tt-button button-primary" target="' . esc_attr( $target ) . '">' . esc_html( $button_text ) . '</a>';
			}

			echo '</div>';
		}

		/**
		 * Render support tab.
		 *
		 * @since 1.0.0
		 */
		public function support() {

			echo '<div class="feature-section three-col">';

			if ( ! empty( $this->config['support_content'] ) ) {

				$support_steps = $this->config['support_content'];

				if ( ! empty( $support_steps ) ) {

					foreach ( $support_steps as $support_step ) {

						echo '<div class="col">';

						if ( ! empty( $support_step['title'] ) ) {
							echo '<h3>';
							if ( ! empty( $support_step['icon'] ) ) {
								echo '<i class="' . esc_attr( $support_step['icon'] ) . '"></i>';
							}
							echo esc_html( $support_step['title'] );
							echo '</h3>';
						}

						if ( ! empty( $support_step['text'] ) ) {
							echo '<p><i>' . wp_kses_post( $support_step['text'] ) . '</i></p>';
						}

						if ( ! empty( $support_step['button_link'] ) && ! empty( $support_step['button_label'] ) ) {

							echo '<p>';
							$button_class = '';
							if ( $support_step['is_button'] ) {
								$button_class = 'button tt-button button-primary';
							}

							$button_new_tab = '_self';
							if ( isset( $support_step['is_new_tab'] ) ) {
								if ( $support_step['is_new_tab'] ) {
									$button_new_tab = '_blank';
								}
							}
							echo '<a target="' . esc_attr( $button_new_tab ) . '" href="' . esc_url( $support_step['button_link'] ) . '" class="' . esc_attr( $button_class ) . '">' . esc_html( $support_step['button_label'] ) . '</a>';
							echo '</p>';
						}

						echo '</div>';

					}
				}
			}

			echo '</div>';
		}

		/**
		 * Render changelog tab.
		 *
		 * @since 1.0.0
		 */
		public function changelog() {

			$changelog_txt = '';

			$changelog = THEMEBEEZ_TOOLKIT_THEME_PATH . '/readme.txt';

			if ( ! file_exists( $changelog ) ) {
				$changelog = esc_html__( 'Changelog file not found.', 'themebeez-toolkit' );
			} elseif ( ! is_readable( $changelog ) ) {
				$changelog = esc_html__( 'Changelog file not readable.', 'themebeez-toolkit' );
			} else {

				global $wp_filesystem;

				// Check if the the global filesystem isn't setup yet.
				if ( is_null( $wp_filesystem ) ) {

					WP_Filesystem();
				}

				$changelog = $wp_filesystem->get_contents( $changelog );

				// Match changelog header.
				$changelog = explode( '== Changelog ==', $changelog );

				if ( is_array( $changelog ) && isset( $changelog[1] ) ) {
					$changelog_txt = $changelog[1];
				}
			}
			?>
			<div class="changelog-section">
				<div class="changelog-detail">					
					<pre><?php echo esc_html( $changelog_txt ); ?></pre>
				</div>
			</div>
			<?php
		}

		/**
		 * Check if plugin is active.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return array Status detail.
		 */
		public function check_if_plugin_active( $slug ) {

			$output = array(
				'status' => null,
				'needs'  => null,
			);

			$is_installed = $this->is_plugin_installed( $slug );

			if ( true === $is_installed ) {
				// Installed.
				$status = $this->is_plugin_active( $slug );
				if ( false === $status ) {
					// Plugin is inactive.
					$output = array(
						'status' => $status,
						'needs'  => 'activate',
					);
				} else {
					// Plugin is active.
					$output = array(
						'status' => $status,
						'needs'  => 'deactivate',
					);
				}
			} else {
				// Not installed.
				$output = array(
					'status' => false,
					'needs'  => 'install',
				);
			}

			return $output;
		}

		/**
		 * Create action link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $state State.
		 * @param string $slug  Plugin slug.
		 * @return string Plugin detail.
		 */
		public function create_action_link( $state, $slug ) {

			$file_path = $this->get_plugin_basename_from_slug( $slug );

			switch ( $state ) {
				case 'install':
					$action_url = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $slug,
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);
					break;
				case 'deactivate':
					$action_url = add_query_arg(
						array(
							'action'        => 'deactivate',
							'plugin'        => rawurlencode( $file_path ),
							'plugin_status' => 'all',
							'paged'         => '1',
							'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $file_path ),
						),
						network_admin_url( 'plugins.php' )
					);
					break;
				case 'activate':
					$action_url = add_query_arg(
						array(
							'action'        => 'activate',
							'plugin'        => rawurlencode( $file_path ),
							'plugin_status' => 'all',
							'paged'         => '1',
							'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $file_path ),
						),
						network_admin_url( 'plugins.php' )
					);
					break;
			}

			return esc_url_raw( $action_url );
		}

		/**
		 * Callback for AJAX dismiss recommended action.
		 *
		 * @since 1.0.0
		 */
		public function dismiss_recommended_action_callback() {

			$todo      = ( isset( $_GET['todo'] ) ) ? esc_attr( wp_unslash( $_GET['todo'] ) ) : ''; // phpcs:ignore
			$action_id = ( isset( $_GET['id'] ) ) ? esc_attr( wp_unslash( $_GET['id'] ) ) : ''; // phpcs:ignore
			$wpnonce   = ( isset( $_GET['_wpnonce'] ) ) ? esc_attr( wp_unslash( $_GET['_wpnonce'] ) ) : ''; // phpcs:ignore

			$nonce = 'action-' . $action_id . '-' . $todo;

			if ( false === wp_verify_nonce( $wpnonce, $nonce ) ) {
				wp_die();
			}

			$action_detail = array();

			$recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
			if ( ! empty( $recommended_actions ) ) {
				foreach ( $recommended_actions['content'] as $action ) {
					$action_detail[ $action['id'] ] = true;
				}
			}

			$options = get_option( $this->action_key );
			if ( $options ) {
				$action_detail = array_merge( $action_detail, $options );
			}

			switch ( $todo ) {
				case 'add':
					$action_detail[ $action_id ] = true;
					break;

				case 'dismiss':
					$action_detail[ $action_id ] = false;
					break;

				default:
					break;
			}

			update_option( $this->action_key, $action_detail );

			wp_die();
		}

		/**
		 * Helper function to extract the file path of the plugin file from the
		 * plugin slug, if the plugin is installed.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug (typically folder name).
		 * @return string Either file path for plugin if installed, or just the plugin slug.
		 */
		private function get_plugin_basename_from_slug( $slug ) {
			$keys = array_keys( $this->get_plugins() );

			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}

			return $slug;
		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin_folder Optional. Relative path to single plugin folder.
		 * @return array Array of installed plugins with plugin information.
		 */
		public function get_plugins( $plugin_folder = '' ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugins( $plugin_folder );
		}

		/**
		 * Check if a plugin is installed.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return bool True if installed, false otherwise.
		 */
		private function is_plugin_installed( $slug ) {
			$installed_plugins = $this->get_plugins(); // Retrieve a list of all installed plugins (WP cached).

			$file_path = $this->get_plugin_basename_from_slug( $slug );

			return ( ! empty( $installed_plugins[ $file_path ] ) );
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return bool True if active, false otherwise.
		 */
		private function is_plugin_active( $slug ) {
			$file_path = $this->get_plugin_basename_from_slug( $slug );

			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return is_plugin_active( $file_path );
		}
	}
}

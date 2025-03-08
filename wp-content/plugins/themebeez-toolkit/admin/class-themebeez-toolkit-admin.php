<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Toolkit
 * @subpackage Themebeez_Toolkit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Themebeez_Toolkit
 * @subpackage Themebeez_Toolkit/admin
 * @author     themebeez <themebeez@gmail.com>
 */
class Themebeez_Toolkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register admin dashboard widgets.
	 *
	 * @since 1.0.0
	 */
	public function register_dashboard_widget() {

		wp_add_dashboard_widget(
			'tt_themebeez_blog_posts_dashboard_widget',
			esc_html__( 'Latest Posts From Themebeez', 'themebeez-toolkit' ),
			array( $this, 'blog_posts_dashboard_widget_callback' )
		);
	}

	/**
	 * Callback for 'tt_themebeez_blog_posts_dashboard_widget' admin dashboard widget.
	 *
	 * @since 1.0.0
	 */
	public function blog_posts_dashboard_widget_callback() {

		$args = array(
			'show_author'  => 0,
			'show_date'    => 1,
			'show_summary' => 0,
			'items'        => 10,
		);

		echo '<div class="tt-rss-feed">';

		wp_widget_rss_output( 'https://themebeez.com/blog/feed/', $args );

		echo '</div>';

		$urls = array(
			'main_site_url' => array(
				'text'               => esc_html__( 'Main Site', 'themebeez-toolkit' ),
				'url'                => 'https://themebeez.com/?utm_source=dashboard&utm_medium=widget&utm_campaign=userdashboard',
				'screen_reader_text' => esc_html__( 'Open in a new tab', 'themebeez-toolkit' ),
				'icon'               => 'dashicons dashicons-external',
			),
			'theme_url'     => array(
				'text'               => esc_html__( 'All Themes', 'themebeez-toolkit' ),
				'url'                => 'https://themebeez.com/themes/?utm_source=dashboard&utm_medium=widget&utm_campaign=userdashboard',
				'screen_reader_text' => esc_html__( 'Open in a new tab', 'themebeez-toolkit' ),
				'icon'               => 'dashicons dashicons-external',
			),
			'blog_url'      => array(
				'text'               => esc_html__( 'Blog Posts', 'themebeez-toolkit' ),
				'url'                => 'https://themebeez.com/blog/?utm_source=dashboard&utm_medium=widget&utm_campaign=userdashboard',
				'screen_reader_text' => esc_html__( 'Open in a new tab', 'themebeez-toolkit' ),
				'icon'               => 'dashicons dashicons-external',
			),
		);

		echo '<p class="community-events-footer">';

		$total_url_count = count( $urls );

		$url_index = 0;

		foreach ( $urls as $index => $url_content ) {

			echo '<a href="' . esc_url( $url_content['url'] ) . '" target="_blank">';

			echo esc_html( $url_content['text'] );

			echo '<span class="screen-reader-text">(' . esc_html( $url_content['screen_reader_text'] ) . ')</span> <span aria-hidden="true" class="' . esc_attr( $url_content['icon'] ) . '"></span>';

			echo '</a>';

			echo ( $index !== $total_url_count ) ? ' | ' : '';
		}

		echo '</p>';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Themebeez_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Themebeez_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/themebeez-toolkit-admin.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Themebeez_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Themebeez_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}
}

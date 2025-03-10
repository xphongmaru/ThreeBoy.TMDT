<?php

namespace TheplusAddons\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'WPINC' ) ) {
	die; }

if ( ! class_exists( 'L_Theplus_Elements_Widgets' ) ) {

	/**
	 * Define L_Theplus_Elements_Widgets class
	 */
	class L_Theplus_Elements_Widgets extends Widget_Base {

		public function __construct() {
			parent::__construct();
			$this->add_actions();
		}

		public function get_name() {
			return 'plus-elementor-widget';
		}

		public function register_controls_widget_magic_scroll( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);
			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}
			$widget->add_control(
				'magic_scroll',
				array(
					'label'       => esc_html__( 'Magic Scroll', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
				)
			);
			$widget->add_control(
				'magic_scroll_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'magic_scroll' => array( 'yes' ),
					),
				)
			);
		}
		public function register_controls_widget_tooltip( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);

			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}

			$widget->add_control(
				'plus_tooltip',
				array(
					'label'       => esc_html__( 'Tooltip', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'plus_tooltip_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'plus_tooltip' => array( 'yes' ),
					),
				)
			);
		}

		public function register_controls_widget_mouseparallax( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);

			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}

			$widget->add_control(
				'plus_mouse_move_parallax',
				array(
					'label'       => esc_html__( 'Mouse Move Parallax', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'plus_mouse_move_parallax_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'plus_mouse_move_parallax' => array( 'yes' ),
					),
				)
			);
		}

		public function register_controls_widget_tilt_parallax( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);

			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}

			$widget->add_control(
				'plus_tilt_parallax',
				array(
					'label'       => esc_html__( 'Tilt 3D Parallax', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'plus_tilt_parallax_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'plus_tilt_parallax' => array( 'yes' ),
					),
				)
			);
		}
		public function register_controls_widget_reveal_effect( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);

			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}

			$widget->add_control(
				'plus_overlay_effect',
				array(
					'label'       => esc_html__( 'Overlay Special Effect', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'plus_overlay_effect_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'plus_overlay_effect' => array( 'yes' ),
					),
				)
			);
		}

		public function register_controls_widget_continuous_animation( $widget, $widget_id, $args ) {
			static $widgets = array(
				'section_plus_extra_adv', /* Section */
			);

			if ( ! in_array( $widget_id, $widgets ) ) {
				return;
			}

			$widget->add_control(
				'plus_continuous_animation',
				array(
					'label'       => esc_html__( 'Continuous Animation', 'tpebl' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_on'    => esc_html__( 'Yes', 'tpebl' ),
					'label_off'   => esc_html__( 'No', 'tpebl' ),
					'render_type' => 'template',
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'plus_continuous_animation_options',
				array(
					'label'       => esc_html__( 'Unlock more possibilities', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'description' => theplus_pro_ver_notice(),
					'classes'     => 'plus-pro-version',
					'condition'   => array(
						'plus_continuous_animation' => array( 'yes' ),
					),
				)
			);
		}
		protected function add_actions() {
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_magic_scroll' ), 10, 3 );
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_tooltip' ), 10, 3 );
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_mouseparallax' ), 10, 3 );
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_tilt_parallax' ), 10, 3 );
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_reveal_effect' ), 10, 3 );
			add_action( 'elementor/element/before_section_end', array( $this, 'register_controls_widget_continuous_animation' ), 10, 3 );
		}
	}

}

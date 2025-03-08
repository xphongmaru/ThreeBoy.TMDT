<?php
/**
 * Extending default Walker_Nav_Menu for simple mega menu.
 *
 * @since 1.1.5
 *
 * @package Themebeez_Toolkit
 */

/**
 * Class - Simple_Mega_Menu_Nav_Walker.
 * Extends Walker_Nav_Menu class.
 *
 * @since 1.1.5
 */
class Simple_Mega_Menu_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * Adds classes to the unordered list sub-menus.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		// Depth-dependent classes.
		$indent        = ( $depth > 0 ) ? str_repeat( "\t", $depth ) : ''; // Code indent.
		$display_depth = ( $depth + 1 ); // Because it counts the first submenu as 0.
		$classes       = array(
			'sub-menu',
			( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
			( $display_depth >= 2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth,
		);

		$class_names = implode( ' ', $classes );

		// Build HTML for output.
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	/**
	 * Start the element output.
	 *
	 * Adds main/sub-classes to the list items and links.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global $wp_query;

		$mega_menu_group = get_post_meta( $item->ID, 'menu-item-mega-menu-group-field', true );
		$icon            = get_post_meta( $item->ID, 'menu-item-icon-field', true );

		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // Code indent.

		// Depth-dependent classes.
		$depth_classes = array(
			( 0 === $depth ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth,
		);

		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		// Passed classes.
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

		$mega_menu_class = '';
		if ( $mega_menu_group && 0 === $depth ) {
			$mega_menu_class = ' menu-item-has-mega-children';
		}

		if ( $mega_menu_group && 1 === $depth ) {
			$mega_menu_class .= ' mega-sub-menu-group';
		}

		// Build HTML.
		$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . $mega_menu_class . '">';

		// Link attributes.
		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

		// Build HTML output and pass through the proper filter.
		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		if ( ! empty( $icon ) ) {
			$item_output .= '<i class="fa ' . esc_attr( $icon ) . '"></i>';
		}
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
		if ( ! empty( $item->description ) ) {
			$item_output .= '<span class="menu-item-description">' . $item->description . '</span>';
		}
		$item_output .= $args->link_after . '</a>';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

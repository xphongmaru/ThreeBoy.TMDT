<?php
/**
 * Post Widget Class
 *
 * @package Orchid_Store
 */

if ( ! class_exists( 'Orchid_Store_Post_Widget' ) ) {
	/**
	 * Widget class - Orchid_Store_Post_Widget.
	 *
	 * @since 1.0.0
	 *
	 * @package orchit_store
	 */
	class Orchid_Store_Post_Widget extends WP_Widget {

		/**
		 * Define id, name and description of the widget.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct(
				'orchid-store-post-widget',
				esc_html__( 'OS: Recent Blog Posts', 'orchid-store' ),
				array(
					'description' => esc_html__( 'Displays posts.', 'orchid-store' ),
				)
			);
		}


		/**
		 * Renders widget at the frontend.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Provides the HTML you can use to display the widget title class and widget content class.
		 * @param array $instance The settings for the instance of the widget..
		 */
		public function widget( $args, $instance ) {

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$no_of_posts = isset( $instance['no_of_posts'] ) ? $instance['no_of_posts'] : 3;

			$show_excerpt = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : true;

			$show_categories = isset( $instance['show_categories'] ) ? $instance['show_categories'] : true;

			$show_author = isset( $instance['show_author'] ) ? $instance['show_author'] : true;

			$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : true;

			$blog_posts = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => absint( $no_of_posts ),
				)
			);
			?>
			<section class="os-blog os-blog-style-1 section-spacing">
				<div class="section-inner">
					<div class="__os-container__">
						<?php
						if ( ! empty( $title ) ) {
							?>
							<div class="section-title">
								<h2><?php echo esc_html( $title ); ?></h2>
							</div><!-- .section-title -->
							<?php
						}
						?>
						<div class="blog-entry">
							<!-- <div class="os-row"> -->
							<div class="blog-list">
								<?php
								if ( $blog_posts->have_posts() ) {

									while ( $blog_posts->have_posts() ) {

										$blog_posts->the_post();
										?>
										<!-- <div class="os-col blog-col"> -->
										<div class="blog-item">
											<div class="card wow osfadeInUp" data-wow-duration="1.5s" data-wow-delay="0.2s">
												<div class="thumb imghover">
													<?php
													/**
													 * Hook - orchid_store_large_thumbnail.
													 *
													 * @hooked orchid_store_large_thumbnail_action - 10
													 */
													do_action( 'orchid_store_large_thumbnail' );

													if ( $show_categories ) {

														/**
														* Hook - orchid_store_post_categories.
														*
														* @hooked orchid_store_post_categories_action - 10
														*/
														do_action( 'orchid_store_post_categories' );
													}
													?>
												</div><!-- .thumb -->
												<div class="card-content">
													<div class="title">
														<h3>
															<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
														</h3>
													</div><!-- .title -->
													<?php
													if ( $show_excerpt ) {

														/**
														* Hook - orchid_store_excerpt.
														*
														* @hooked orchid_store_excerpt_action - 10
														*/
														do_action( 'orchid_store_excerpt' );
													}

													if ( $show_author || $show_date ) {
														?>
														<div class="entry-metas">
															<ul>
																<?php
																if ( $show_author ) {

																	/**
																	* Hook - orchid_store_post_author.
																	*
																	* @hooked orchid_store_post_author_action - 10
																	*/
																	do_action( 'orchid_store_post_author' );
																}

																if ( $show_date ) {

																	/**
																	* Hook - orchid_store_post_date.
																	*
																	* @hooked orchid_store_post_date_action - 10
																	*/
																	do_action( 'orchid_store_post_date' );
																}
																?>
															</ul>
														</div><!-- .entry-metas -->
														<?php
													}
													?>
												</div> <!-- // card-content -->
											</div><!-- // card -->
										</div><!-- .col -->
										<?php
									}
								} else {
									?>
									<div class="col-12">
										<div class="nothing-found-title">
											<p><?php esc_html_e( 'There are no posts to display.', 'orchid-store' ); ?></p>
											<?php
											if ( current_user_can( 'publish_posts' ) ) {
												printf(
													'<p>' . wp_kses(
														/* translators: 1: link to WP admin new post page. */
														__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'orchid-store' ),
														array(
															'a' => array(
																'href' => array(),
															),
														)
													) . '</p>',
													esc_url( admin_url( 'post-new.php' ) )
												);
											}
											?>
										</div>
									</div>
									<?php
								}
								?>
							</div><!-- .row -->
						</div><!-- .blog-entry -->
					</div><!-- .__os-container__ -->
				</div><!-- .section-inner -->
			</section><!-- .os-blog -->
			<?php
		}


		/**
		 * Adds setting fields to the widget and renders them in the form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance The settings for the instance of the widget..
		 */
		public function form( $instance ) {
			$defaults = array(
				'title'           => '',
				'no_of_posts'     => 3,
				'show_excerpt'    => true,
				'show_categories' => true,
				'show_author'     => true,
				'show_date'       => true,

			);

			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<strong><?php esc_html_e( 'Title', 'orchid-store' ); ?></strong>
				</label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $instance['title'] ); ?>"
				/>   
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'no_of_posts' ) ); ?>">
					<strong><?php esc_html_e( 'No of Posts', 'orchid-store' ); ?></strong>
				</label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'no_of_posts' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'no_of_posts' ) ); ?>"
					type="number"
					value="<?php echo esc_attr( absint( $instance['no_of_posts'] ) ); ?>"
				/>   
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" <?php checked( absint( $instance['show_excerpt'] ), 1 ); ?>
					>                
					<strong>
						<?php esc_html_e( 'Display Post Excerpt', 'orchid-store' ); ?>
					</strong>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'show_categories' ) ); ?>" <?php checked( absint( $instance['show_categories'] ), 1 ); ?>
					>                
					<strong><?php esc_html_e( 'Display Post Categories', 'orchid-store' ); ?></strong>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'show_author' ) ); ?>" <?php checked( absint( $instance['show_author'] ), 1 ); ?>
					>                
					<strong>
						<?php esc_html_e( 'Display Post Author', 'orchid-store' ); ?>
					</strong>
				</label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" <?php checked( absint( $instance['show_date'] ), 1 ); ?>
					>                
					<strong>
						<?php esc_html_e( 'Display Date', 'orchid-store' ); ?>
					</strong>
				</label>
			</p>           
			<?php
		}


		/**
		 * Sanitizes and saves the instance of the widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance The settings for the new instance of the widget.
		 * @param array $old_instance The settings for the old instance of the widget.
		 * @return array Sanitized instance of the widget.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title']           = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['no_of_posts']     = isset( $new_instance['no_of_posts'] ) ? absint( $new_instance['no_of_posts'] ) : 3;
			$instance['show_excerpt']    = isset( $new_instance['show_excerpt'] ) ? true : false;
			$instance['show_categories'] = isset( $new_instance['show_categories'] ) ? true : false;
			$instance['show_author']     = isset( $new_instance['show_author'] ) ? true : false;
			$instance['show_date']       = isset( $new_instance['show_date'] ) ? true : false;

			return $instance;
		}
	}
}

<?php

use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Brikk_Uts_Elementor_Articles extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-articles';
	}

	public function get_title() {
		return __( 'Articles', 'brikk-utilities' );
	}

	public function get_icon() {
		return 'rz-icon-brikk';
	}

	public function get_categories() {
		return [
            'brikk'
        ];
	}

	protected function _register_controls() {

        /*
         * >>>>> section content
         *
         */
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'brikk-utilities' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => 1,
			]
		);

		$categories = [];
        $terms = get_categories([
            'hide_empty' => false
        ]);
        foreach( $terms as $term ) {
            $categories[ $term->term_id ] = $term->name;
        }

        $this->add_control(
			'categories',
			[
				'label' => __( 'Select Category', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'label_block' => true,
			]
		);

		$this->add_control(
			'no_excerpt', [
				'label' => __( 'Hide excerpt', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

        $this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		extract( $settings );

		$is_wide_page = ( function_exists('Brk') and Brk()->is_wide_page() );
        $posts_per_page = $is_wide_page ? 5 : 3;

        $articles = new \WP_Query([
			'post_type' => 'post',
			'post_status' => 'publish',
            'posts_per_page' => $posts_per_page * $rows,
            'ignore_sticky_posts' => true,
			'category__in' => is_array( $categories ) ? $categories : [],
		]);

        ob_start(); ?>

		<section class="brk-section brk-elementor-row">
            <div class="brk-flex--full brk-justify-center">

                <?php if( $articles->have_posts() ): ?>

                    <div class="brk-articles<?php if( $no_excerpt ) { echo ' brk-no-excerpt'; } ?>" data-cols="<?php if( $is_wide_page ) { echo '5'; }else{ echo '3'; } ?>">
                        <div class="brk-grid brk-justify-center">
                            <?php while( $articles->have_posts() ): $articles->the_post(); ?>
                                <?php get_template_part( 'templates/article' ); ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>

                <?php else: ?>
                    <p><?php esc_html_e( 'No articles were found.', 'brikk-utilities' ) ?></p>
                <?php endif; ?>

            </div>
        </section>

		<?php echo ob_get_clean();

	}

}

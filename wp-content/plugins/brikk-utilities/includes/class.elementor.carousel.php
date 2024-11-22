<?php

class Brikk_Uts_Elementor_Carousel extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-carousel';
	}

	public function get_title() {
		return __( 'Carousel', 'brikk-utilities' );
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
			'style',
			[
                'label' => __( 'Form Style', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'brikk-utilities' ),
					'vertical' => esc_html__( 'Vertical', 'brikk-utilities' ),
                ],
				'label_block' => true,
				'default' => 'horizontal',
			]
		);

		$this->add_control(
			'text_position',
			[
                'label' => __( 'Text Position', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    'top' => __( 'Top', 'brikk-utilities' ),
                    'bottom' => __( 'Bottom', 'brikk-utilities' ),
                ],
				'label_block' => true,
				'default' => 'top',
				'condition' => [
                    'style' => 'horizontal'
                ],
			]
		);

		$this->add_control(
			'title', [
				'label' => __( 'Title', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Some Title' , 'brikk-utilities' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'tag', [
				'label' => __( 'Top Tag', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'span' => 'SPAN',
                ],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'bottom_text', [
				'label' => __( 'Bottom Text', 'brikk-utilities' ),
				'description' => __( 'You can use some basic html tags, like <br>', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);

		$this->add_control(
			'tag_bottom', [
				'label' => __( 'Bottom Tag', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'span' => 'SPAN',
                ],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'enable_cta', [
				'label' => __( 'Enable Call-to-Action', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

		$this->add_control(
			'cta_label', [
				'label' => __( 'Call-to-Action Label', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Button Label' , 'brikk-utilities' ),
				'label_block' => true,
                'condition' => [
                    'enable_cta' => 'yes'
                ],
			]
		);

		$this->add_control(
			'cta_url', [
				'label' => __( 'Call-to-Action URL', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '#',
				'label_block' => true,
                'condition' => [
                    'enable_cta' => 'yes'
                ],
			]
		);

        /*
         * repeater
         *
         */
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'name', [
				'label' => __( 'Name', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Name' , 'brikk-utilities' ),
				'label_block' => true,
			]
		);

        $search_forms = [];
        $post_types = get_posts([
            'post_type' => 'rz_search_form',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);
        foreach( $post_types as $post_type ) {
            $search_forms[ $post_type->ID ] = $post_type->post_title;
        }

        $repeater->add_control(
			'search_form',
			[
				'label' => __( 'Select Search Form', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => $search_forms,
				'label_block' => true,
			]
		);

		$this->add_control(
			'listing_types',
			[
				'label' => __( 'Listing Types', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
			]
		);

        $this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		if( $settings['style'] == 'vertical' ) {
            $settings['text_position'] = 'top';
        }

        ob_start(); ?>

        <div class="brk--cont-heading brk-active">
            <header class="brk--main" <?php /* ?>style="<?php if( $text_color ) { echo sprintf( 'color: %s;', esc_html( $text_color ) ); } ?>"<?php */ ?>>
                <<?php echo esc_html( $settings['tag'] ); ?> class="brk--title">
                    <?php echo wp_kses( html_entity_decode( $settings['title'] ), Rz()->allowed_html() ); ?>
                </<?php echo esc_html( $settings['tag'] ); ?>>
                <?php if( $settings['bottom_text'] ): ?>
                    <<?php echo esc_html( $settings['tag_bottom'] ); ?> class="brk--bottom-text">
                        <?php echo wp_kses( html_entity_decode( $settings['bottom_text'] ), Rz()->allowed_html() ); ?>
                    </<?php echo esc_html( $settings['tag_bottom'] ); ?>>
                <?php endif; ?>
                <?php if( $settings['enable_cta'] == 'yes' ): ?>
                    <div class="brk--cta">
                        <a href="<?php echo esc_url( Rz()->format_url( $settings['cta_url'] ) ); ?>" class="rz-button rz-button-accent rz-large brk--button-cta">
                            <span><?php echo esc_html( $settings['cta_label'] ); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </header>
        </div><?php

        $heading = ob_get_clean();

        ob_start(); ?>

        <section class="brk-section brk-elementor-row">
            <div class="brk-carousel-search brk-flex--full brk-justify-center brk--style-<?php echo esc_attr( $settings['style'] ); ?><?php if( $settings['style'] == 'horizontal' ) { echo ' brk--position-' . esc_attr( $settings['text_position'] ); } ?>">
                <div class="brk--container">

                    <?php if( $settings['text_position'] == 'top' ) { echo do_shortcode( $heading ); } ?>

                    <!-- navigation -->
                    <?php if( count( $settings['listing_types'] ) > 1 ): ?>
                        <ul class="brk-carousel-nav">
                            <?php foreach( $settings['listing_types'] as $key => $listing_type ): ?>
                                <li data-for="<?php echo (int) $key; ?>" class="<?php if( $key == 0 ) { echo 'brk-active'; } ?>">
                                    <a href="#" class="<?php if( $settings['style'] == 'horizontal' ) { echo 'brk-bg'; } ?>">
                                        <span>
                                            <?php
                                                if( ! empty( $listing_type['name'] ) ) {
                                                    echo esc_html( $listing_type['name'] );
                                                }else{
                                                    echo '-';
                                                }
                                            ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- search -->
                    <?php foreach( $settings['listing_types'] as $key => $listing_type ): ?>
                        <div class="brk--content brk--cont-search<?php if( $key == 0 ) { echo ' brk-active'; } ?>" data-id="<?php echo (int) $key; ?>">
                            <div class="brk-carousel-search-type">
                                <?php if( $listing_type['search_form'] ): ?>
                                    <?php echo do_shortcode("[rz-search-form id='{$listing_type['search_form']}']"); ?>
                                <?php endif; ?>
								<?php if( current_user_can('manage_options') ): ?>
                                    <div class="rz-mt-2">
                                        <a href="<?php echo esc_url( get_edit_post_link( $listing_type['search_form'] ) ); ?>" class="rz-no-decoration rz-text-right" target="_blank">
                                            <?php esc_html_e( 'Edit search form', 'brikk-utilities' ); ?>
                                            <i class="fas fa-external-link-alt rz-ml-1"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if( $settings['text_position'] == 'bottom' ) { echo do_shortcode( $heading ); } ?>

                </div>
            </div>
        </section>

        <?php echo ob_get_clean();

	}

}

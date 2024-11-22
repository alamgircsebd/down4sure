<?php

class Brikk_Uts_Elementor_Heading extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-heading';
	}

	public function get_title() {
		return __( 'Heading', 'brikk-utilities' );
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
			'title',
			[
				'label' => __( 'Title', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Title', 'brikk-utilities' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title_tag', [
				'label' => __( 'Title Tag', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
					'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'DEV',
                    'span' => 'SPAN',
                    'p' => 'P',
                ],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' => __( 'Title Size', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    'small' => __( 'Small', 'brikk-utilities' ),
                    'medium' => __( 'Medium', 'brikk-utilities' ),
                    'large' => __( 'Large', 'brikk-utilities' ),
                    'xl' => __( 'XL', 'brikk-utilities' ),
                    'xxl' => __( 'XXL', 'brikk-utilities' ),
                    'xxxl' => __( 'XXXL', 'brikk-utilities' ),
                ],
				'default' => 'large',
			]
		);

		$this->add_control(
			'sub_title',
			[
				'label' => __( 'Sub-Title', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'sub_title_url',
			[
				'label' => __( 'Sub-Title URL ( Optional )', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'sub_title_icon', [
				'label' => __( 'Sub-Title Icon ( Optional )', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'exclude_inline_options' => [
                    'svg'
                ],
			]
		);

		$this->add_control(
			'sub_title_tag', [
				'label' => __( 'Sub-Title Tag', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'DEV',
                    'span' => 'SPAN',
                    'p' => 'P',
                ],
				'default' => 'p',
			]
		);

		$this->add_control(
			'sub_title_size',
			[
				'label' => __( 'Sub-Title Size', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    'small' => __( 'Small', 'brikk-utilities' ),
                    'medium' => __( 'Medium', 'brikk-utilities' ),
                    'large' => __( 'Large', 'brikk-utilities' ),
                    'xl' => __( 'XL', 'brikk-utilities' ),
                    'xxl' => __( 'XXL', 'brikk-utilities' ),
                    'xxxl' => __( 'XXXL', 'brikk-utilities' ),
                ],
				'default' => 'small',
			]
		);

        $this->end_controls_section();

		/*
         * >>>>> section styles
         *
         */
        $this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'brikk-utilities' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        /*
         * bg image
         *
         */
        $this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => 'inherit',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		extract( $this->get_settings_for_display() );

        ob_start(); ?>

		<div class="brk-section brk-elementor-row">
			<div class="brk--heading" style="<?php if( $text_color ) { echo sprintf( 'color: %s;', $text_color ); } ?>">
                <<?php echo esc_attr( $title_tag ); ?> class="brk--title brk--size-<?php echo esc_attr( $title_size ); ?>">
                    <?php echo wp_kses( html_entity_decode( $title ), Rz()->allowed_html() ); ?>
                </<?php echo esc_attr( $title_tag ); ?>>
                <?php if( $sub_title ): ?>
                    <<?php echo esc_attr( $sub_title_tag ); ?> class="brk--sub-title brk--size-<?php echo esc_attr( $sub_title_size ); ?>">
						<?php if( $sub_title_url ): ?><a href="<?php echo esc_url( Rz()->format_url( $sub_title_url ) ); ?>"><?php endif; ?>
							<?php echo wp_kses( html_entity_decode( $sub_title ), Rz()->allowed_html() ); ?>
							<?php if( ! empty( $sub_title_icon['value'] ) ): ?>
								<i class="brk-ml-1 <?php echo esc_html( $sub_title_icon['value'] ); ?>"></i>
							<?php endif; ?>
						<?php if( $sub_title_url ): ?></a><?php endif; ?>
					</<?php echo esc_attr( $sub_title_tag ); ?>>
                <?php endif; ?>
		    </div>
	    </div>

        <?php echo ob_get_clean();

	}

}

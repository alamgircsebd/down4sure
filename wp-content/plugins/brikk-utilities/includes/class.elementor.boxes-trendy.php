<?php

class Brikk_Uts_Elementor_Boxes_Trendy extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-boxes-trendy';
	}

	public function get_title() {
		return __( 'Boxes Trendy', 'brikk-utilities' );
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
			'columns',
			[
				'label' => __( 'Columns', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 10,
				'step' => 1,
				'default' => 7,
			]
		);

		$this->add_control(
			'style',
			[
                'label' => __( 'URI Location', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'rounded' => esc_html__( 'Rounded', 'brikk-utilities' ),
					'square' => esc_html__( 'Square', 'brikk-utilities' ),
                ],
				'label_block' => true,
				'default' => 'rounded',
			]
		);

		$this->add_control(
			'double_height', [
				'label' => __( 'Double Box Height', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
				'condition' => [
                    'style' => 'square'
                ],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'name', [
				'label' => __( 'Name', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Name' , 'brikk-utilities' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'overlay',
			[
				'label' => __( 'Overlay Element', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'url', [
				'label' => __( 'URI', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '#' , 'brikk-utilities' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'icon', [
				'label' => __( 'Icon', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'skin' => 'inline',
				'exclude_inline_options' => [
                    'svg'
                ],
			]
		);

		$repeater->add_control(
			'has_overlay', [
				'label' => __( 'Enable Overlay', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

		$this->add_control(
			'boxes',
			[
				'label' => __( 'Boxes', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
			]
		);

        $this->end_controls_section();

	}

	protected function render() {

		extract( $this->get_settings_for_display() );

        ob_start(); ?>

		<section class="brk-section brk-elementor-row">
            <div class="brk-flex--full brk-justify-center">

                <div class="brk-boxes-trendy-container">
                    <div class="brk-boxes-trendy brk--<?php echo esc_html( $style ); if( $double_height ) { echo ' brk--double-height'; } ?> brk-no-select brk--x<?php echo (int) $columns; ?>">
                        <?php foreach( $boxes as $box ): ?>

                            <?php

								$image_url = '';
								if( $box['image']['id'] ) {
									$image_src = wp_get_attachment_image_src( $box['image']['id'], 'brk_trendy' );
									if( isset( $image_src[0] ) ) {
										$image_url = $image_src[0];
									}elseif( ! empty( $box['image']['url'] ) ) {
										$image_url = $box['image']['url'];
									}
								}

                            ?>

                            <div class="brk--cell">
                                <a href="<?php echo esc_url( Rz()->format_url( $box['url'] ) ); ?>" class="brk--item">
                                    <?php if( $image_url ): ?>
										<div class="brk--image" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
                                            <div class="brk--inner">
												<?php if( $box['has_overlay'] ): ?>
													<span class="brk--overlay"></span>
												<?php endif; ?>
                                                <?php if( ! empty( $box['icon']['value'] ) ): ?>
                                                    <span class="brk--icon"><i class="<?php echo esc_html( $box['icon']['value'] ); ?>"></i></span>
                                                <?php endif; ?>
												<?php if( $box['overlay']['value'] ): ?>
													<div class="rz--overlay-element">
														<?php if( is_array( $box['overlay']['value'] ) ): ?>
															<img src="<?php echo esc_url( $box['overlay']['value']['url'] ); ?>" alt="<?php echo esc_attr( $box['name'] ); ?>">
														<?php else: ?>
															<i class="<?php echo esc_html( $box['overlay']['value'] ); ?>"></i>
														<?php endif; ?>
													</div>
												<?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php echo Rz()->dummy(); ?>
                                    <?php endif; ?>
                                    <span class="brk--name brk-font-heading">
                                        <?php echo esc_html( $box['name'] ); ?>
                                    </span>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </section>

        <?php echo ob_get_clean();

	}

}

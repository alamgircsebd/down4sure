<?php

class Brikk_Uts_Elementor_Types extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-types';
	}

	public function get_title() {
		return __( 'Types', 'brikk-utilities' );
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
			'height',
			[
				'label' => __( 'Height %', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 50,
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
			'description', [
				'label' => __( 'Description', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
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
			'url', [
				'label' => __( 'URL', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_label', [
				'label' => __( 'Button Label', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'types',
			[
				'label' => __( 'Types', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
			]
		);

		$this->add_control(
			'enable_overlay', [
				'label' => __( 'Enable overlay', 'brikk-utilities' ),
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

		extract( $this->get_settings_for_display() );

        ob_start(); ?>

		<section class="brk-section">
            <div class="brk-flex--full brk-justify-center">

                <div class="brk-types-container">
                    <div class="brk-types" style="height: <?php echo (int) $height; ?>vh;">
                        <?php foreach( $types as $type ): ?>

							<?php

								$image_url = '';
								if( $type['image']['id'] ) {
									$image_src = wp_get_attachment_image_src( $type['image']['id'], 'brk_box_main' );
									if( isset( $image_src[0] ) ) {
										$image_url = $image_src[0];
									}elseif( ! empty( $type['image']['url'] ) ) {
										$image_url = $type['image']['url'];
									}
								}

							?>

							<?php if( ! empty( $type['url'] ) ): ?><a class="brk--cell" href="<?php echo esc_url( Rz()->format_url( $type['url'] ) ); ?>"><?php else: ?><div class="brk--cell"><?php endif; ?>

								<span class="rz--cover" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></span>

								<span class="rz--content">

									<?php if( ! empty( $type['name'] ) ): ?>
	                                    <h4 class="brk--name">
	                                        <?php echo wp_kses( html_entity_decode( $type['name'] ), Rz()->allowed_html() ); ?>
	                                    </h4>
									<?php endif; ?>

									<?php if( ! empty( $type['description'] ) ): ?>
	                                    <div class="brk--description">
	                                        <?php echo do_shortcode( nl2br( $type['description'] ) ); ?>
	                                    </div>
									<?php endif; ?>

									<?php if( ! empty( $type['button_label'] ) ): ?>
										<span class="rz-button rz-large rz--border rz--white">
											<?php echo esc_html( $type['button_label'] ); ?>
										</span>
									<?php endif; ?>

                                </span>

                            <?php if( ! empty( $type['url'] ) ): ?></a><?php else: ?></div><?php endif; ?>

                        <?php endforeach; ?>
                    </div>
					<?php if( $enable_overlay ): ?>
						<span class="brk--shadow"></span>
					<?php endif; ?>
                </div>

            </div>
        </section>

        <?php echo ob_get_clean();

	}

}

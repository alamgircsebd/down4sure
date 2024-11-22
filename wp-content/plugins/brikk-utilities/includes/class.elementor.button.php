<?php

class Brikk_Uts_Elementor_Button extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-button';
	}

	public function get_title() {
		return __( 'Button', 'brikk-utilities' );
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

		/*
         * repeater
         *
         */
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Text', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Button', 'brikk-utilities' ),
			]
		);

		$repeater->add_control(
			'url',
			[
				'label' => __( 'URL', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '#',
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
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'style', [
				'label' => __( 'Style', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'brikk-utilities' ),
					'accent' => __( 'Accent', 'brikk-utilities' ),
					'border' => __( 'Border', 'brikk-utilities' ),
                ],
				'default' => 'h2',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'white', [
				'label' => __( 'White Color', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
				'condition' => [
                    'style' => 'border'
                ],
			]
		);

		$repeater->add_control(
			'new_tab', [
				'label' => __( 'Open in a New Tab', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

		$repeater->add_control(
			'large', [
				'label' => __( 'Large', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

		$this->add_control(
			'buttons',
			[
				'label' => __( 'Buttons', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ text }}}',
			]
		);

        $this->end_controls_section();

	}

	protected function render() {

		extract( $this->get_settings_for_display() );

        ob_start(); ?>

		<div class="brk-section brk-elementor-row">
			<div class="brk-buttons">
				<?php foreach( $buttons as $button ): ?>
					<?php
						$classes = [];

						if( $button['large'] ) {
							$classes[] = 'rz-large';
						}

						switch( $button['style'] ) {
							case 'border':
								$classes[] = 'rz--border';
								if( $button['white'] ) {
									$classes[] = 'rz--white';
								}
								break;
							case 'accent':
								$classes[] = 'rz-button-accent'; break;
						}
					?>
					<div class="brk--button">
						<a href="<?php echo esc_url( Rz()->format_url( $button['url'] ) ); ?>" class="rz-button <?php echo implode( ' ', $classes ); ?>"<?php if( $button['new_tab'] ) { echo ' target="_blank"'; } ?>>
							<span><?php echo esc_html( $button['text'] ); ?></span>
							<?php if( ! empty( $button['icon']['value'] ) ): ?>
								<i class="rz-ml-1 <?php echo esc_attr( $button['icon']['value'] ); ?>"></i>
							<?php endif; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
	    </div>

        <?php echo ob_get_clean();

	}

}

<?php

class Brikk_Uts_Elementor_Cards extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-cards';
	}

	public function get_title() {
		return __( 'Cards', 'brikk-utilities' );
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
			'url', [
				'label' => __( 'URL', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'icon', [
				'label' => __( 'Icon', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'button_text', [
				'label' => __( 'Button Text', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'cards',
			[
				'label' => __( 'Cards', 'brikk-utilities' ),
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

                <div class="brk-cards-container">
                    <div class="brk-cards">
                        <?php foreach( $cards as $card ): ?>

                            <div class="brk--cell">
                                <?php if( $card['url'] ): ?><a href="<?php echo esc_url( Rz()->format_url( $card['url'] ) ); ?>" class="brk--item"><?php else: ?><span class="brk--item"><?php endif; ?>
                                    <?php if( ! empty( $card['icon']['value'] ) ): ?>
                                        <?php if( is_array( $card['icon']['value'] ) ): ?>
											<img src="<?php echo esc_url( $card['icon']['value']['url'] ); ?>" alt="">
                                        <?php else: ?>
											<i class="<?php echo esc_attr( $card['icon']['value'] ); ?>"></i>
                                        <?php endif; ?>
                                    <?php endif; ?>
									<?php if( ! empty( $card['name'] ) ): ?>
	                                    <h4 class="brk--name">
	                                        <?php echo wp_kses( html_entity_decode( $card['name'] ), Rz()->allowed_html() ); ?>
	                                    </h4>
									<?php endif; ?>
									<?php if( ! empty( $card['description'] ) ): ?>
	                                    <div class="brk--description">
	                                        <?php echo do_shortcode( nl2br( $card['description'] ) ); ?>
	                                    </div>
									<?php endif; ?>
									<?php if( ! empty( $card['button_text'] ) ): ?>
	                                    <div class="brk--footer">
	                                        <span class="rz-button rz--border rz-block">
												<span>
													<?php echo esc_html( $card['button_text'] ); ?>
												</span>
											</span>
	                                    </div>
									<?php endif; ?>
                                <?php if( $card['url'] ): ?></a><?php else: ?></span><?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </section>

        <?php echo ob_get_clean();

	}

}

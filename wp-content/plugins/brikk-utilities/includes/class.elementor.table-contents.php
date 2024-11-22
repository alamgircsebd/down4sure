<?php

class Brikk_Uts_Elementor_Table_Contents extends \Elementor\Widget_Base {

	public function get_name() {
		return 'brk-table-contents';
	}

	public function get_title() {
		return __( 'Table of Contents', 'brikk-utilities' );
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
			'price', [
				'label' => __( 'Price', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'text', [
				'label' => __( 'Text', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_text', [
				'label' => __( 'Button Text', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_url', [
				'label' => __( 'Button URL', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'highlight', [
				'label' => __( 'Highlight Column', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'brikk-utilities' ),
				'label_off' => __( 'No', 'brikk-utilities' ),
				'return_value' => 'yes',
				'default' => false,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Table Column', 'brikk-utilities' ),
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

                <div class="brk-pricing">
                    <?php foreach( $columns as $column ): ?>

                        <div class="brk--cell">
                            <div class="brk--column<?php if( $column['highlight'] ) { echo ' brk--highlight'; } ?>">
                                <?php if( $column['highlight'] ): ?>
                                    <span class="brk--badge brk-bg">
                                        <span><?php esc_html_e( 'Most Popular', 'brikk-utilities' ); ?></span>
                                    </span>
                                <?php endif; ?>
                                <div class="brk--name">
                                    <span class="brk--price"><?php echo esc_html( $column['price'] ); ?></span>
                                    <span class="brk--title brk-font-heading"><?php echo esc_html( $column['name'] ); ?></span>
									<?php if( ! empty( $column['description'] ) ): ?>
	                                    <span class="brk--description brk-font-heading">
											<?php echo do_shortcode( html_entity_decode( $column['description'] ) ); ?>
										</span>
									<?php endif; ?>
                                </div>
								<?php if( ! empty( $column['text'] ) ): ?>
	                                <div class="brk--text">
	                                    <?php echo do_shortcode( html_entity_decode( $column['text'] ) ); ?>
	                                </div>
								<?php endif; ?>
                                <div class="brk--footer">
                                    <?php if( $column['button_text'] ): ?>
                                        <a href="<?php echo esc_url( Rz()->format_url( $column['button_url'] ) ); ?>" class="rz-button <?php if( $column['highlight'] ) { echo 'rz-button-accent'; }else{ echo 'rz-white-gray'; } ?>">
                                            <span><?php echo esc_html( $column['button_text'] ); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>
        </section>

        <?php echo ob_get_clean();

	}

}

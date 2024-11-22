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
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Sub-Title', 'brikk-utilities' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

        $this->end_controls_section();

	}

	protected function render() {

		extract( $this->get_settings_for_display() );

        ob_start(); ?>

		<div class="brk-section brk-elementor-row">
			<div class="brk--heading">
		        <div class="brk--inner">
		            <div class="brk--content">
		                <h3 class="brk--title">
		                    <?php echo wp_kses( html_entity_decode( $title ), Rz()->allowed_html() ); ?>
		                </h3>
		                <?php if( $subtitle ): ?>
		                    <p><?php echo wp_kses( html_entity_decode( Rz()->format_url( $subtitle ) ), Rz()->allowed_html() ); ?></p>
		                <?php endif; ?>
		            </div>
		        </div>
		    </div>
	    </div>

        <?php echo ob_get_clean();

	}

}

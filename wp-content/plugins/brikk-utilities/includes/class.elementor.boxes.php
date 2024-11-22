<?php

class Brikk_Uts_Elementor_Boxes extends \Elementor\Widget_Base {

    protected $image_sizes = [
        1 => [ 'x2' ],
        2 => [ 'x2','x2' ],
        3 => [ 'x2','x2','x2' ],
        4 => [ 'x2','x2','x1','x1' ],
        5 => [ 'x2','x1','x1','x1','x1' ],
        6 => [ 'x2','x1','x1','x2','x1','x1' ],
        7 => [ 'x2','x1','x1','x1','x1','x1','x1' ],
        8 => [ 'x1','x1','x1','x1','x1','x1','x1','x1' ],
    ];

    public function get_name() {
        return 'brk-boxes';
    }

    public function get_title() {
        return __( 'Boxes', 'brikk-utilities' );
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
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Description' , 'brikk-utilities' ),
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
            'location',
            [
                'label' => __( 'URI Location', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'listing_type' => __( 'Listing Type', 'brikk-utilities' ),
                    'custom' => __( 'Custom', 'brikk-utilities' ),
                ],
                'label_block' => true,
                'default' => 'listing_type',
            ]
        );

        $listing_types = [];
        $post_types = get_posts([
            'post_type' => 'rz_listing_type',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);
        foreach( $post_types as $post_type ) {
            $listing_types[ $post_type->ID ] = $post_type->post_title;
        }

        $repeater->add_control(
            'location_listing_type',
            [
                'label' => __( 'Select Listing Type', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $listing_types,
                'label_block' => true,
                'condition' => [
                    'location' => 'listing_type'
                ],
            ]
        );

        $repeater->add_control(
            'location_custom', [
                'label' => __( 'Custom Location URI', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '#',
                'label_block' => true,
                'condition' => [
                    'location' => 'custom'
                ],
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

        $num_boxes = min( 8, count( $boxes ) );

        ob_start(); ?>

        <section class="brk-section brk-elementor-row">
            <div class="brk-flex--full brk-justify-center">
                <div class="brk-bxs-container">
                    <div class="brk-bxs brk--dynamic" data-items="<?php echo (int) $num_boxes; ?>">
                        <?php foreach( $boxes as $key => $box ): ?>
                            <?php if( $key >= 8 ) { break; } ?>
                            <?php $is_large = $this->image_sizes[ (int) $num_boxes ][ $key ] == 'x2'; ?>
                            <div class="brk--cell">

                                <?php

                                    $location = $box['location'];
                                    $location_listing_type = $box['location_listing_type'];
                                    $location_custom = $box['location_custom'];
                                    $url = $location == 'custom' ? $location_custom : Rz()->get_explore_page_url([ 'type' => Rz()->get_meta( 'rz_slug', $location_listing_type ) ]);

                                    $image_url = '';
                                    if( $box['image']['id'] ) {
                                        $image_size = $is_large ? 'brk_box_main' : 'brk_box';
                                        $image_src = wp_get_attachment_image_src( $box['image']['id'], $image_size );
                                        if( isset( $image_src[0] ) ) {
                                            $image_url = $image_src[0];
                                        }elseif( ! empty( $box['image']['url'] ) ) {
                                            $image_url = $box['image']['url'];
                                        }
                                    }

                                ?>

                                <a href="<?php echo esc_url( Rz()->format_url( $url ) ); ?>"
                                    class="brk-bx-item brk--<?php if( $is_large ) { echo 'x2'; }else{ echo 'x1'; } ?>"
                                    <?php if( $image_url ): ?> style="background-image: url('<?php echo esc_url( $image_url ); ?>');"<?php endif; ?>>
                                    <div class="brk--content">
                                        <span class="brk--name brk-font-heading">
                                            <?php echo esc_html( $box['name'] ); ?>
                                        </span>
                                        <?php if( $box['description'] ): ?>
                                            <span class="brk--desc">
                                                <?php echo esc_html( $box['description'] ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
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

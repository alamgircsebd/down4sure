<?php

use \Routiz\Inc\Src\Listing_Type\Listing_Type;
use \Routiz\Inc\Src\Listing\Listing;

class Brikk_Uts_Elementor_Listing_Boxes extends \Elementor\Widget_Base {

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
        return 'brk-listing-boxes';
    }

    public function get_title() {
        return __( 'Listing Boxes', 'brikk-utilities' );
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
            'posts_per_page',
            [
                'label' => __( 'Number of Listings', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => __( 'Style', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'dynamic' => esc_html__('Dynamic', 'brikk-utilities'),
                    'static' => esc_html__('Static', 'brikk-utilities'),
                ],
                'label_block' => true,
                'default' => 'dynamic',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 3,
                'condition' => [
                    'style' => 'static'
                ],
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

        $this->add_control(
            'listing_type',
            [
                'label' => __( 'Select Listing Type', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $listing_types,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'sorting',
            [
                'label' => __( 'Sorting', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'latest' => esc_html__('Latest', 'brikk-utilities'),
                    'top_rated' => esc_html__('Top Rated', 'brikk-utilities'),
                    'priority' => esc_html__('Priority', 'brikk-utilities'),
                    'random' => esc_html__('Random', 'brikk-utilities'),
                ],
                'label_block' => true,
                'default' => 'latest',
            ]
        );

        $taxonomies = [
            'none' => __( 'None', 'brikk-utilities' ),
            'rz_listing_category' => __( 'Categories', 'brikk-utilities' ),
            'rz_listing_region' => __( 'Regions', 'brikk-utilities' ),
            'rz_listing_tag' => __( 'Tags', 'brikk-utilities' ),
        ];

        // add custom taxonomies
        $custom_taxonomies = Rz()->get_custom_taxonomies();
        foreach( $custom_taxonomies as $custom_taxonomy ) {
            $taxonomies[ Rz()->prefix( $custom_taxonomy->slug ) ] = $custom_taxonomy->name;
        }

        $this->add_control(
            'hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label' => __( 'Taxonomy', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $taxonomies,
            ]
        );

        foreach( $taxonomies as $tax_id => $tax_name ) {
            $terms = [];
            $terms_obj = get_terms( $tax_id, [
                'hide_empty' => false,
            ]);
            if( empty( $terms_obj ) or is_wp_error( $terms_obj ) ) {
                continue;
            }
            foreach( $terms_obj as $term_obj ) {
                $terms[ $term_obj->term_id ] = $term_obj->name;
            }
            $this->add_control(
                sprintf( 'terms-%s', $tax_id ),
                [
                    'label' => sprintf( __( 'Terms - %s', 'brikk-utilities' ), $tax_name ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => $terms,
                    'label_block' => true,
                    'multiple' => true,
                    'condition' => [
                        'taxonomy' => $tax_id
                    ],
                ]
            );
        }

        $this->add_control(
            'field',
            [
                'label' => __( 'Select Field to Display', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'brikk-utilities'),
                    'rz_listing_region' => esc_html__('Region', 'brikk-utilities'),
                    'rz_listing_category' => esc_html__('Category', 'brikk-utilities'),
                    'rz_listing_tag' => esc_html__('Tags', 'brikk-utilities'),
                ],
                'label_block' => true,
                'default' => 'latest',
            ]
        );

        $this->add_control(
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

        $this->add_control(
            'show_rating', [
                'label' => __( 'Show Rating', 'brikk-utilities' ),
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

    global $brk_field;

    $settings = $this->get_settings_for_display();

    extract( $settings );

    $is_wide_page = ( function_exists('Brk') and Brk()->is_wide_page() );
    // $posts_per_page = $is_wide_page ? 5 : 3;

    if( ! $listing_type ) {
        $listing_type = 'all'; // Set a default value to indicate that all listing types should be fetched
    }

    $brk_field = $field;

    // Check if listing type is set to 'all' (default value)
    if( $listing_type === 'all' ) {
        // Don't filter by listing type
        $meta_query = [];
    } else {
        // Filter by selected listing type
        $listing_type_obj = new Listing_Type( $listing_type );

        if( ! $listing_type_obj->post || $listing_type_obj->post->post_status !== 'publish' ) {
            return;
        }

        $meta_query = [
            [
                'key' => 'rz_listing_type',
                'value' => $listing_type_obj->id,
                'compare' => '=',
            ]
        ];
    }

    if( $taxonomy and $taxonomy !== 'none' ) {
        $terms = $settings[ sprintf( 'terms-%s', $taxonomy ) ];
        if( $terms ) {
            $meta_query[] = [
                'key' => $taxonomy,
                'value' => $terms,
                'compare' => 'IN',
            ];
        }
    }

    $url_params = [
        'type' => $listing_type_obj ? $listing_type_obj->get('rz_slug') : '', // Use listing type slug if available
    ];

    $posts_per_page = min( 24, $posts_per_page );

    $args = [
        'post_type' => 'rz_listing',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'meta_query' => $meta_query,
    ];

    switch( $sorting ) {
        case 'top_rated':

            global $wpdb;

            $results = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT *
                    FROM {$wpdb->posts}, {$wpdb->options} as o
                    WHERE {$wpdb->posts}.post_type = 'rz_listing'
                    AND {$wpdb->posts}.post_status = 'publish'
                    AND o.option_name LIKE CONCAT( '_transient_rz_reviews_average_%', {$wpdb->posts}.ID )
                    GROUP BY {$wpdb->posts}.ID
                    ORDER BY option_value DESC
                ", $posts_per_page )
            );

            $post_ids = [];
            foreach( $results as $row ) {
                $post_ids[] = $row->ID;
            }

            $args['post__in'] = $post_ids;
            $args['orderby'] = 'post__in';

            break;
        case 'priority':
            $args['meta_key'] = 'rz_priority';
            $args['orderby'] = [
                'meta_value_num' => 'DESC',
                'date' => 'DESC',
            ];
            $args['order'] = 'DESC';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
    }

    $args['meta_query'] = $meta_query;
    $listings = new \WP_Query( $args );
    $more_posts = $listings->post_count - $posts_per_page;
    $num_boxes = min( 24, $listings->post_count );

    ob_start(); ?>

    <section class="brk-section brk-elementor-row">
        <div class="brk-flex--full brk-justify-center">

            <?php if( $listings->have_posts() ): ?>

                <div class="brk-bxs-container">
                    <div class="brk-bxs brk--<?php echo esc_attr( $style ); ?>"<?php if( $style == 'static' ): ?> data-cols="<?php echo (int) $columns; ?>"<?php endif; ?> data-items="<?php echo (int) $num_boxes; ?>">
                        <?php while( $listings->have_posts() ): $listings->the_post(); ?>
                            <div class="brk--cell">

                                <?php

                                $listing = new Listing( get_the_ID() );
                                $is_large = false;
                                if( $style == 'dynamic' ) {
                                    $is_large = $this->image_sizes[ (int) $num_boxes ][ $listings->current_post ] == 'x2';
                                }
                                $image_size = $is_large ? 'brk_box_main' : 'brk_box';

                                if( has_post_thumbnail( $listing->id ) ) {
                                    $image = get_the_post_thumbnail_url( $listing->id, $image_size );
                                }else{
                                    $image = $listing->get_first_from_gallery( $image_size );
                                }

                                ?>

                                <a href="<?php echo esc_url( Rz()->format_url( get_permalink( $listing->id ) ) ); ?>"
                                   class="brk-bx-item brk--<?php if( $is_large ) { echo 'x2'; }else{ echo 'x1'; } ?>"<?php if( $image ): ?>
                                   style="background-image: url('<?php echo esc_url( $image ); ?>');"<?php endif; ?>>

                                    <?php if( ! $image ): ?>
                                        <?php echo Rz()->dummy(); ?>
                                    <?php endif; ?>

                                    <div class="brk--post">
                                        <?php if( $show_rating and $listing->reviews->average ): ?>
                                            <div class="rz--review">
                                                <i class="fas fa-star"></i>
                                                <span>
                                                    <?php echo number_format( $listing->reviews->average, 2 ); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="brk--bottom">

                                            <?php if( $brk_field ): ?>

                                                <?php
                                                $details = [];
                                                $value = Rz()->get_meta( $brk_field, $listing->id, false );
                                                if( is_array( $value ) ) {
                                                    foreach( $value as $val ) {
                                                        $term = get_term( $val, $brk_field );
                                                        if( $term and ! is_wp_error( $term ) ) {
                                                            $term_icon = get_term_meta( $term->term_id, 'rz_icon', true );
                                                            $details[] = (object)[
                                                                'type' => 'text',
                                                                'content' => $term->name,
                                                            ];
                                                        }
                                                    }
                                                }
                                                ?>

                                                <?php if( $details ): ?>
                                                    <div class="rz--details">
                                                        <?php if( ! empty( $icon['value'] ) ): ?>
                                                            <i class="rz-mr-1 <?php echo esc_attr( $icon['value'] ); ?>"></i>
                                                        <?php endif; ?>
                                                        <ul>
                                                            <?php foreach( $details as $detail ): ?>
                                                                <li><span><?php echo esc_html( $detail->content ); ?></span></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                            <?php endif; ?>

                                            <span class="brk--title brk-font-heading">
                                                <?php echo get_the_title( $listing->id ); ?>
                                            </span>

                                        </div>
                                    </div>

                                </a>

                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>

            <?php else: ?>
                <p class="brk-text-center"><strong><?php esc_html_e( 'No listings were found.', 'brikk-utilities' ) ?></strong></p>
            <?php endif; ?>

        </div>
    </section>

    <?php echo ob_get_clean();

}
}
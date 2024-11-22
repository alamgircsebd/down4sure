<?php

use \Routiz\Inc\Src\Listing_Type\Listing_Type;

class Brikk_Uts_Elementor_Listings extends \Elementor\Widget_Base {

    public function get_name() {
        return 'brk-listings';
    }

    public function get_title() {
        return __( 'Listings', 'brikk-utilities' );
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
            'rows',
            [
                'label' => __( 'Rows', 'brikk-utilities' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 99,
                'step' => 1,
                'default' => 1,
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
                'multiple' => true,  // Enable multiple selections
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

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        extract( $settings );

        $is_wide_page = ( function_exists('Brk') and Brk()->is_wide_page() );
        $posts_per_page = $is_wide_page ? 5 : 3;

        if( empty( $listing_type ) ) {
            echo esc_html_e('Please select a listing type', 'brikk-utilities');
            return;
        }

        $meta_query = [
            'relation' => 'AND',
            [
                'key' => 'rz_listing_type',
                'value' => $listing_type, // Handle multiple listing types
                'compare' => 'IN',
            ]
        ];

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

        $listing_type = (array) $listing_type; // Add this line
        $url_params = [
            'type' => implode(',', $listing_type) // Handle multiple types in URL
        ];


        $args = [
            'post_type' => 'rz_listing',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page * $rows,
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
                    ", $posts_per_page * $rows )
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

        $listings = new \WP_Query( $args );

        ob_start(); ?>

        <section class="brk-section brk-elementor-row">
            <div class="brk-flex--full brk-justify-center">

                <div class="brk-listings">
                    <?php if( $listings ): ?>

                        <ul class="rz-listings" <?php if( $is_wide_page ) { echo 'data-cols="5"'; } ?>>
                            <?php while( $listings->have_posts() ): $listings->the_post() ?>
                                <li class="rz-listing-item <?php Rz()->listing_class(); ?>">
                                    <?php Rz()->the_template('routiz/explore/listing/listing'); ?>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>

                    <?php else: ?>
                        <p><?php esc_html_e( 'No results were found.', 'brikk-utilities' ) ?></p>
                    <?php endif; ?>

                </div>

            </div>
        </section>

        <?php echo ob_get_clean();

    }

}

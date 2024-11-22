<?php

class Brikk_Uts_Elementor {

    function __construct() {

        add_action( 'init', [ $this, 'init_widgets' ] );

    }

    public function init_widgets() {

        if( ! function_exists('Rz') or ! function_exists('Brk') ) {
            return;
        }

        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'preview_enqueue_scripts' ] );

        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

        add_action( 'elementor/elements/categories_registered', [ $this, 'create_custom_categories' ] );

    }

    public function preview_enqueue_scripts() {

        wp_enqueue_style( 'brk-uts-elementor', BRK_UTS_URI . 'assets/dist/css/elementor.css', [], BRK_UTS_VERSION );

    }

    public function register_widgets() {

        $this->include_widgets_files();

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Carousel() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Heading() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Button() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Boxes() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Boxes_Trendy() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Listings() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Listing_Boxes() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Table_Contents() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Articles() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Cards() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Brikk_Uts_Elementor_Types() );

    }

    public function include_widgets_files() {

        require BRK_UTS_PATH . 'includes/class.elementor.carousel.php';
        require BRK_UTS_PATH . 'includes/class.elementor.heading.php';
        require BRK_UTS_PATH . 'includes/class.elementor.button.php';
        require BRK_UTS_PATH . 'includes/class.elementor.boxes.php';
        require BRK_UTS_PATH . 'includes/class.elementor.boxes-trendy.php';
        require BRK_UTS_PATH . 'includes/class.elementor.listings.php';
        require BRK_UTS_PATH . 'includes/class.elementor.listing-boxes.php';
        require BRK_UTS_PATH . 'includes/class.elementor.table-contents.php';
        require BRK_UTS_PATH . 'includes/class.elementor.articles.php';
        require BRK_UTS_PATH . 'includes/class.elementor.cards.php';
        require BRK_UTS_PATH . 'includes/class.elementor.types.php';

    }

    public function create_custom_categories( $elements_manager ) {

        $elements_manager->add_category(
            'brikk',
            [
                'title' => __( 'Brikk', 'brikk-utilities' ),
                'icon' => 'fas fa-location-arrow',
            ]
        );

        $category_prefix = 'brikk';

        $reorder_cats = function() use( $category_prefix ) {
            uksort( $this->categories, function( $keyOne, $keyTwo ) use( $category_prefix ) {
                if( substr( $keyOne, 0, 5 ) == $category_prefix ) {
                    return -1;
                }
                if( substr( $keyTwo, 0, 5 ) == $category_prefix ) {
                    return 1;
                }
                return 0;
            });

        };

        $reorder_cats->call($elements_manager);

        $elements_manager->add_category(
            'brikk',
            [
                'title' => __( 'Brikk', 'brikk-utilities' ),
                'icon' => 'fas fa-location-arrow',
            ]
        );

    }

}

new Brikk_Uts_Elementor();

<?php

namespace Routiz\Inc\Src\Woocommerce;

class Post_Types {

    use \Routiz\Inc\Src\Traits\Singleton;

    function __construct() {

        add_action( 'init', [ $this, 'register_post_types' ] );
        add_action( 'init', [ $this, 'register_post_status' ], 12 );

        add_action( 'updated_post_meta', [ $this, 'after_plan_update_count' ], 10, 4 );

        foreach([ 'post', 'post-new' ] as $hook ) {
			add_action( "admin_footer-{$hook}.php", [ $this, 'extend_plan_post_status' ] );
			add_action( "admin_footer-{$hook}.php", [ $this, 'extend_promotion_post_status' ] );
			add_action( "admin_footer-{$hook}.php", [ $this, 'extend_entry_post_status' ] );
			add_action( "admin_footer-{$hook}.php", [ $this, 'extend_claim_post_status' ] );
		}

    }

    function register_post_types() {

        $is_multisite = is_multisite();
        $capability_create = $is_multisite ? 'do_not_allow' : false;

        $singular = esc_html__( 'Plan', 'routiz' );
		$plural = esc_html__( 'Plans', 'routiz' );

		$rewrite = [
			'slug' => 'plan',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_plan',
			apply_filters( 'routiz/post_type/plan', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,

                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

        $singular = esc_html__( 'Promotion', 'routiz' );
		$plural = esc_html__( 'Promotions', 'routiz' );

		$rewrite = [
			'slug' => 'promotion',
			'with_front' => false,
			'feeds' => false,
			'pages' => false
		];

		register_post_type( 'rz_promotion',
			apply_filters( 'routiz/post_type/promotion', [
				'labels' => [
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( esc_html__( '%s', 'routiz' ), $plural ),
					'add_new' 				=> esc_html__( 'Add New', 'routiz' ),
					'add_new_item' 			=> sprintf( esc_html__( 'Add %s', 'routiz' ), $singular ),
					'edit' 					=> esc_html__( 'Edit', 'routiz' ),
					'edit_item' 			=> sprintf( esc_html__( 'Edit %s', 'routiz' ), $singular ),
					'new_item' 				=> sprintf( esc_html__( 'New %s', 'routiz' ), $singular ),
					'view' 					=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'view_item' 			=> sprintf( esc_html__( 'View %s', 'routiz' ), $singular ),
					'search_items' 			=> sprintf( esc_html__( 'Search %s', 'routiz' ), $plural ),
					'not_found' 			=> sprintf( esc_html__( 'No %s found', 'routiz' ), $plural ),
					'not_found_in_trash' 	=> sprintf( esc_html__( 'No %s found in trash', 'routiz' ), $plural ),
					'parent' 				=> sprintf( esc_html__( 'Parent %s', 'routiz' ), $singular ),
				],
                'taxonomies'            => [],
                'show_in_menu'          => 'edit.php?post_type=rz_listing_type',
				'public' 				=> false,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> false,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> [ 'title' ],
				'has_archive' 			=> false,
				'show_in_nav_menus' 	=> true,

                'capability_type'       => 'post',
                'capabilities' => [
                    'create_posts' => $capability_create,
                ],
                'map_meta_cap'          => true,
			])
		);

    }

    public function register_post_status() {

        register_post_status( 'cancelled', [
			'label' => _x( 'Cancelled', 'post status', 'routiz' ),
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'routiz' ),
		]);

        register_post_status( 'used', [
			'label' => _x( 'Used', 'post status', 'routiz' ),
			'public' => true,
			'protected' => true,
			'exclude_from_search' => true,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Used <span class="count">(%s)</span>', 'Used <span class="count">(%s)</span>', 'routiz' ),
		]);

    }

    public function extend_plan_post_status() {

        global $post, $post_type;

		if ( $post_type !== 'rz_plan' ) {
			return;
		}

		$options = $display = '';
		foreach ( Rz()->get_plan_statuses() as $status => $name ) {
			$selected = selected( $post->post_status, $status, false );
			if ( $selected ) {
				$display = $name;
			}
			$options .= "<option{$selected} value='{$status}'>" . esc_html( $name ) . '</option>';
		}

		?>
    		<script type="text/javascript">
    			jQuery( document ).ready( function($) {
    				<?php if ( ! empty( $display ) ) : ?>
    					jQuery( '#post-status-display' ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $display ) ); ?>' ) );
    				<?php endif; ?>
    				var select = jQuery( '#post-status-select' ).find( 'select' );
    				jQuery( select ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $options ) ); ?>' ) );
    			} );
    		</script>
		<?php

	}

    public function extend_promotion_post_status() {

        global $post, $post_type;

		if ( $post_type !== 'rz_promotion' ) {
			return;
		}

		$options = $display = '';
		foreach ( Rz()->get_promotion_statuses() as $status => $name ) {
			$selected = selected( $post->post_status, $status, false );
			if ( $selected ) {
				$display = $name;
			}
			$options .= "<option{$selected} value='{$status}'>" . esc_html( $name ) . '</option>';
		}

		?>
    		<script type="text/javascript">
    			jQuery( document ).ready( function($) {
    				<?php if ( ! empty( $display ) ) : ?>
    					jQuery( '#post-status-display' ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $display ) ); ?>' ) );
    				<?php endif; ?>
    				var select = jQuery( '#post-status-select' ).find( 'select' );
    				jQuery( select ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $options ) ); ?>' ) );
    			} );
    		</script>
		<?php

	}

    public function extend_entry_post_status() {

        global $post, $post_type;

		if ( $post_type !== 'rz_entry' ) {
			return;
		}

		$options = $display = '';
		foreach( Rz()->get_entry_statuses() as $status => $name ) {
			$selected = selected( $post->post_status, $status, false );
			if ( $selected ) {
				$display = $name;
			}
			$options .= "<option{$selected} value='{$status}'>" . esc_html( $name ) . '</option>';
		}

		?>
    		<script type="text/javascript">
    			jQuery( document ).ready( function($) {
    				<?php if ( ! empty( $display ) ) : ?>
    					jQuery( '#post-status-display' ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $display ) ); ?>' ) );
    				<?php endif; ?>
    				var select = jQuery( '#post-status-select' ).find( 'select' );
    				jQuery( select ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $options ) ); ?>' ) );
    			} );
    		</script>
		<?php

	}

    public function extend_claim_post_status() {

        global $post, $post_type;

		if ( $post_type !== 'rz_claim' ) {
			return;
		}

		$options = $display = '';
		foreach( Rz()->get_claim_statuses() as $status => $name ) {
			$selected = selected( $post->post_status, $status, false );
			if ( $selected ) {
				$display = $name;
			}
			$options .= "<option{$selected} value='{$status}'>" . esc_html( $name ) . '</option>';
		}

		?>
    		<script type="text/javascript">
    			jQuery( document ).ready( function($) {
    				<?php if ( ! empty( $display ) ) : ?>
    					jQuery( '#post-status-display' ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $display ) ); ?>' ) );
    				<?php endif; ?>
    				var select = jQuery( '#post-status-select' ).find( 'select' );
    				jQuery( select ).html( decodeURIComponent( '<?php echo rawurlencode( (string) wp_specialchars_decode( $options ) ); ?>' ) );
    			} );
    		</script>
		<?php

	}

    public function after_plan_update_count( $meta_id, $post_id, $meta_key, $meta_value ) {

        if( get_post_type( $post_id ) !== 'rz_plan' ) {
            return;
        }

        if ( $meta_key == 'rz_count' ) {

            $limit = (int) get_post_meta( $post_id, 'rz_limit', true );

            // unlimited
            if( $limit == 0 ) {
                return;
            }

            if( metadata_exists( 'post', $post_id, 'rz_limit' ) ) {
                if( $meta_value >= $limit ) {
                    wp_update_post([
                        'ID' => $post_id,
                        'post_status' => 'used'
                    ]);
                }
            }
        }

    }

}

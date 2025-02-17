<rz-preview-listing :props='{{ json_encode( $props ) }}' inline-template>

    <div class="rz-form">
        <div class="rz-grid">
            <div class="rz-col-6 rz-col-lg-12">
                <div class="rz-grid">
                    @php

                        $component->render([
                            'type' => 'select',
                            'id' => 'display_listing_type',
                            'name' => esc_html__('Type', 'routiz'),
                            'v_model' => 'type',
                            'value' => 'grid',
                            'options' => [
                                'grid' => esc_html__('Grid', 'routiz'),
                                'list' => esc_html__('List', 'routiz'),
                            ],
                            'allow_empty' => false,
                            'col' => 6,
                        ]);

                        $component->render([
                            'type' => 'select',
                            'id' => 'display_listing_cover',
                            'name' => esc_html__('Cover', 'routiz'),
                            'v_model' => 'cover',
                            'value' => 'slider',
                            'options' => [
                                'none' => esc_html__('None', 'routiz'),
                                'image' => esc_html__('Image', 'routiz'),
                                'slider' => esc_html__('Slider', 'routiz'),
                                'author' => esc_html__('Author', 'routiz'),
                            ],
                            'allow_empty' => false,
                            'col' => 6,
                        ]);

                        $component->render([
                            'type' => 'checkbox',
                            'id' => 'display_hide_listing_details',
                            'name' => esc_html__('Hide Listing Details?', 'routiz'),
                            'v_model' => 'hide_listing_details',
                        ]);

                        $component->render([
                            'type' => 'checkbox',
                            'id' => 'display_listing_favorite',
                            'name' => esc_html__('Enable favorite?', 'routiz'),
                            'value' => true,
                            'v_model' => 'favorite',
                            'dependency' => [
                                'id' => 'display_listing_cover',
                                'value' => [
                                    'slider',
                                    'image',
                                ],
                                'compare' => 'IN',
                            ],
                        ]);

                        $component->render([
                            'type' => 'checkbox',
                            'id' => 'display_listing_review',
                            'name' => esc_html__('Enable Review Rating?', 'routiz'),
                            'value' => true,
                            'v_model' => 'review',
                        ]);

                        $component->render([
                            'type' => 'use_field',
                            'id' => 'display_listing_title',
                            'name' => esc_html__('Select Title Field', 'routiz'),
                            'v_model' => 'title',
                            'col' => 6,
                        ]);

                        $component->render([
                            'type' => 'use_field',
                            'id' => 'display_listing_tagline',
                            'name' => esc_html__('Select Tagline Field', 'routiz'),
                            'v_model' => 'tagline',
                            'col' => 6,
                        ]);

                        $component->render([
                            'type' => 'repeater',
                            'id' => 'display_listing_content',
                            'name' => esc_html__('Content', 'routiz'),
                            'v_model' => 'content',
                            'templates' => [

                                'label' => [
                                    'name' => esc_html__('Label', 'routiz'),
                                    'heading' => 'key',
                                    'fields' => [
                                        'format' => [
                                            'type' => 'text',
                                            'name' => esc_html__('Format', 'routiz'),
                                            'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: `{field} bedrooms`, will print `4 bedrooms`', 'routiz'),
                                            'value' => '${field}',
                                            'col' => 6,
                                        ],
                                        'key' => [
                                            'type' => 'text',
                                            'name' => esc_html__('Field Id', 'routiz'),
                                            'description' => sprintf( esc_html__('Set the id of the field you want to use for rendering. You can use the custom fields or our pre-defined field ids %s', 'routiz'), '<a href="#" data-modal="field-ids"><strong>' . esc_html__( 'Check what field ids you can use', 'routiz' ) . '</strong></a>' ),
                                            'value' => 'price',
                                            'col' => 6,
                                        ],
                                        'icon' => [
                                            'type' => 'icon',
                                            'name' => esc_html__('Icon', 'routiz'),
                                        ],
                                        'render_format' => [
                                            'type' => 'select',
                                            'name' => esc_html__('Render Format', 'routiz'),
                                            'options' => [
                                                'text' => esc_html__('Text', 'routiz'),
                                                'price' => esc_html__('Price', 'routiz'),
                                            ],
                                            'value' => 'text',
                                            'allow_empty' => false,
                                        ],
                                    ]
                                ],

                                'taxonomy' => [
                                    'name' => esc_html__('Taxonomy', 'routiz'),
                                    'heading' => 'key',
                                    'fields' => [
                                        'key' => [
                                            'type' => 'use_field',
                                            'name' => esc_html__('Select Field', 'routiz'),
                                            'group' => 'taxonomy',
                                        ],
                                        'display_icons' => [
                                            'type' => 'checkbox',
                                            'name' => esc_html__('Display Term Icons?', 'routiz'),
                                        ],
                                    ]
                                ],

                            ]
                        ]);

                        $component->render([
                            'type' => 'repeater',
                            'id' => 'display_listing_bottom',
                            'name' => esc_html__('Bottom Labels', 'routiz'),
                            'v_model' => 'bottom_labels',
                            'templates' => [
                                'label' => [
                                    'name' => esc_html__('Label', 'routiz'),
                                    'heading' => 'key',
                                    'fields' => [
                                        'format' => [
                                            'type' => 'text',
                                            'name' => esc_html__('Format', 'routiz'),
                                            'description' => esc_html__('{field} will be replaced with the field value. You can add additional text, example format: `{field} bedrooms`, will print `4 bedrooms`', 'routiz'),
                                            'value' => '${field}',
                                            'col' => 6,
                                        ],
                                        'key' => [
                                            'type' => 'text',
                                            'name' => esc_html__('Field Id', 'routiz'),
                                            'description' => sprintf( esc_html__('Set the id of the field you want to use for rendering. You can use the custom fields or our pre-defined field ids %s', 'routiz'), '<a href="#" data-modal="field-ids"><strong>' . esc_html__( 'Check what field ids you can use', 'routiz' ) . '</strong></a>' ),
                                            'value' => 'price',
                                            'col' => 6,
                                        ],
                                        'icon' => [
                                            'type' => 'icon',
                                            'name' => esc_html__('Icon', 'routiz'),
                                        ],
                                        'render_format' => [
                                            'type' => 'select',
                                            'name' => esc_html__('Render Format', 'routiz'),
                                            'options' => [
                                                'text' => esc_html__('Text', 'routiz'),
                                                'price' => esc_html__('Price', 'routiz'),
                                            ],
                                            'value' => 'text',
                                            'allow_empty' => false,
                                        ],
                                    ]
                                ],
                            ]
                        ]);

                    @endphp

                    <div class="rz-form-group rz-col-12 rz-text-center rz-mt-3">
                        <button type="submit" class="rz-button rz-large">
                            <span><?php esc_html_e( 'Save Changes', 'routiz' ); ?></span>
                        </button>
                    </div>

                </div>
            </div>
            <div class="rz-col-6 rz-col-lg-12">

                <div class="rz-heading">
                    <label class="rz-ellipsis ">
                        {{ esc_html__( 'Listing Preview', 'routiz' ) }}
                    </label>
                </div>

                <div class="rz-prv rz-flex rz-flex-column rz-justify-center rz-no-select">

                    <div class="rz-prv-grid" :type="type">
                        <template v-if="cover == 'image' || cover == 'slider'">
                            <div class="rz-prv-image">
                                <i class="rz-prv-icon-image far fa-image"></i>
                                <i v-if="favorite" class="rz-prv-icon-favorite fas fa-heart"></i>
                                <div v-if="cover == 'slider'" class="rz-prv-slider">
                                    <i class="rz-prv-slider-arrow-left fas fa-chevron-left"></i>
                                    <i class="rz-prv-slider-arrow-right fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </template>
                        <template v-if="cover == 'author'">
                            <div class="rz-prv-author">
                                <div class="rz-prv-author-image">
                                    <span></span>
                                </div>
                                <div v-if="type == 'grid'" class="rz-prv-author-name">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </template>
                        <template v-if="!hide_listing_details">
                            <div class="rz-prv-content">
                                <div class="rz-prv-heading">
                                    <div class="rz-prv-title">
                                        <h3 v-if="title">{{ esc_html__( 'Listing title, siter amet conselen an actetur adipisced', 'routiz' ) }}</h3>
                                    </div>
                                    <div v-if="review" class="rz-prv-review">
                                        <i class="fas fa-star rz-mr-1"></i>
                                        <strong>5.00</strong>
                                    </div>
                                </div>
                                <p v-if="tagline">{{ esc_html__( 'Suspendisse feugiat ex non lacus maximus, ac efficitur nisi varius', 'routiz' ) }}</p>
                                <ul v-if="content && content !== '[]'" class="rz-prv-labels rz-prv-labels-dark rz-mt-2">
                                    <li v-for="field in JSON.parse( content )"
                                        v-html="format_heading( field.fields.key, field.template.heading_text )">
                                        <!---->
                                    </li>
                                </ul>
                                <ul v-if="bottom_labels && bottom_labels !== '[]'" class="rz-prv-labels rz-prv-labels-dark rz-mt-2">
                                    <li v-for="field in JSON.parse( bottom_labels )"
                                        v-html="format_heading( field.fields.key, field.template.heading_text )">
                                        <!---->
                                    </li>
                                </ul>
                            </div>
                        </template>

                    </div>

                </div>

            </div>
        </div>
    </div>
</rz-preview-listing>

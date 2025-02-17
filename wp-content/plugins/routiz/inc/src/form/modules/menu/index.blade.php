@include('label.index')

<div class="rz-field-menu">
    @php

        $component->render( array_merge( $props, [
            'type' => 'repeater',
            'id' => $props['id'],
            'name' => $props['name'],
            'button' => [
                'label' => $strings->add_section
            ],
            'is_col' => false,
            'templates' => [

                'menu_section' => [
                    'name' => $strings->section,
                    'heading' => 'name',
                    'fields' => [
                        'name' => [
                            'type' => 'text',
                            'name' => $strings->name,
                        ],

                        'items' => [
                            'type' => 'repeater',
                            'name' => $strings->items,
                            'templates' => [

                                'item' => [
                                    'name' => $strings->item,
                                    'heading' => 'name',
                                    'fields' => [
                                        'name' => [
                                            'type' => 'text',
                                            'name' => $strings->name,
                                        ],
                                        'description' => [
                                            'type' => 'text',
                                            'name' => $strings->description,
                                        ],
                                        'price' => [
                                            'type' => 'text',
                                            'name' => $strings->price,
                                        ],
                                        'focus' => [
                                            'type' => 'text',
                                            'name' => $strings->special,
                                            'description' => $strings->short_info,
                                        ],
                                    ]
                                ],
                            ]
                        ]

                    ]
                ]
            ]
        ]));

    @endphp
</div>

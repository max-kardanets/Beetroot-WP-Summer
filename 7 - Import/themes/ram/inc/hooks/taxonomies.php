<?php
add_action('init', function() {
    /**
     * Character status
     */
    register_taxonomy('character_status',
        ['character'],
        [
            'labels' => [
                'name' => 'Statuses',
                'singular_name' => 'Status',
            ],
            'public' => true,
        ]
    );

    /**
     * Character species
     */
    register_taxonomy('character_species',
        ['character'],
        [
            'labels' => [
                'name' => 'Species',
                'singular_name' => 'Species',
            ],
            'public' => true,
        ]
    );

    /**
     * Character type
     */
    register_taxonomy('character_type',
        ['character'],
        [
            'labels' => [
                'name' => 'Types',
                'singular_name' => 'Type',
            ],
            'public' => true,
        ]
    );

    /**
     * Character gender
     */
    register_taxonomy('character_gender',
        ['character'],
        [
            'labels' => [
                'name' => 'Gender',
                'singular_name' => 'Gender',
            ],
            'public' => true,
        ]
    );
});

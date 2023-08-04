<?php
add_action('init', function() {
    /**
     * Characters
     */
    register_post_type('character', [
        'labels' => [
            'name' => 'Characters',
            'singular_name' => 'Character',
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'characters'
        ],
    ]);

    /**
     * Locations
     */
    register_post_type('location', [
        'labels' => [
            'name' => 'Locations',
            'singular_name' => 'Location',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'locations'
        ],
    ]);

    /**
     * Locations
     */
    register_post_type('episode', [
        'labels' => [
            'name' => 'Episodes',
            'singular_name' => 'Episode',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'episodes'
        ],
    ]);
});

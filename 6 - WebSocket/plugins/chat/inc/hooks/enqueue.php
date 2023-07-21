<?php
add_action('wp_enqueue_scripts', function() {
    // CSS
    wp_register_style('chat', CHAT_ROOT_URI .'assets/scss/chat.css', '', CHAT_VER);

    // JS
    wp_register_script('chat', CHAT_ROOT_URI .'assets/js/chat.js', ['jquery'], CHAT_VER, true);

    // JS data
    global $chat_helper;

    $encrypted_user_id = $chat_helper->encrypt_user_id(get_current_user_id());

    wp_localize_script('chat', 'chat_vars', [
        'token' => $encrypted_user_id,
        'ajax_url' => '',
        'templates' => [
            'message' => get_chat_template_part('chat-message', [
                'css' => [
                    'new'
                ]
            ])
        ],
    ]);
});
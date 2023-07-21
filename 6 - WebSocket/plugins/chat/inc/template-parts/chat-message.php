<?php
/**
 * Args
 */

// CSS
$css = (!empty($args['css'])) ? $args['css'] : null;
$user_id = (!empty($args['user_id'])) ? intval($args['user_id']) : null;
$message = (!empty($args['message'])) ? sanitize_text_field($args['message']) : null;
$date = (!empty($args['date'])) ? date('H:i', strtotime($args['date'])) : null;

$user_data = (!empty($user_id)) ? get_userdata($user_id) : null;
$user_name = (!empty($user_data)) ? $user_data->display_name : null;

$name_color = (!empty($user_id)) ? number_to_color($user_id) : null;

if(get_current_user_id() == $user_id) {
    $css[] = 'my';
}

$inline_css = (!empty($css)) ? implode(' ', $css) : null;

/**
 * HTML parts
 */
?>

<div class="message_item <?php echo $inline_css; ?>">
    <div class="message_avatar"></div>
    <div class="message_body">
        <div class="message_author" style="color: <?php echo $name_color; ?>;"><?php echo $user_name; ?></div>
        <div class="message_text">
            <div class="text"><?php echo $message; ?></div>
            <span class="message_time"><?php echo $date; ?></span>
        </div>
    </div>
</div>
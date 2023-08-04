<?php
if(empty($args)) {
    return;
}

$id = $args->ID;
$url = get_permalink($id);
$name = $args->post_title;

// Thumbnail
$thumbnail_id = get_post_thumbnail_id($id);
$thumbnail = (!empty($thumbnail_id)) ? wp_get_attachment_image($thumbnail_id, 'medium_large') : null;

$html_image = null;
if(!empty($thumbnail)) {
    $html_image = '<div class="char_image"><a href="'. $url .'">'. $thumbnail .'</a></div>';
}

// Status
$status = wp_get_post_terms($id, 'character_status');
$status = (!empty($status)) ? $status[0]->name .' - ' : null;

// Type
$type = wp_get_post_terms($id, 'character_type');
$type = (!empty($type[0])) ? $type[0]->name : null;

// Location
$location_id = get_field('location', $id);
$location_url = (!empty($location_id)) ? get_permalink($location_id) : null;
$location_name = (!empty($location_id)) ? get_the_title($location_id) : null;

$html_location = null;
if(!empty($location_id)) {
    $html_location = <<<HTML
<div class="char_info_item">
    <div class="item_label">Last known location:</div>
    <a href="{$location_url}">{$location_name}</a>
</div>
HTML;
}

// Episode
$episode_id = get_field('episode', $id);
$episode_url = (!empty($episode_id)) ? get_permalink($episode_id) : null;
$episode_name = (!empty($episode_id)) ? get_the_title($episode_id) : null;

$html_episode = null;
if(!empty($episode_id)) {
    $html_episode = <<<HTML
<div class="char_info_item">
    <div class="item_label">First seen in:</div>
    <a href="{$episode_url}">{$episode_name}</a>
</div>
HTML;
}
?>
<div class="character_item">
    <div class="item_inner">
        <?php echo $html_image; ?>
        <div class="char_info">
            <div class="char_name"><a href="<?php echo $url; ?>"><?php echo $name; ?></a></div>
            <div class="char_general"><?php echo $status . $type ?></div>
            <?php
                echo $html_location;
                echo $html_episode;
            ?>
        </div>
    </div>
</div>
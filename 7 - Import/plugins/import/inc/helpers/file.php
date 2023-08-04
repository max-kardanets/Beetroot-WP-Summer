<?php
function upload_and_attach_image( $image_url, $post_id = null ) {
    // it allows us to use download_url() and wp_handle_sideload() functions
    require_once ABSPATH . 'wp-admin/includes/file.php';

    // download to temp dir
    $temp_file = download_url( $image_url );

    if( is_wp_error($temp_file)) {
        return false;
    }

    // move the temp file into the uploads directory
    $file = array(
        'name'     => basename( $image_url ),
        'type'     => mime_content_type( $temp_file ),
        'tmp_name' => $temp_file,
        'size'     => filesize($temp_file),
    );
    $sideload = wp_handle_sideload($file, ['test_form' => false]);

    if(!empty($sideload['error'])) {
        return false;
    }

    // it is time to add our uploaded image into WordPress media library
    $attachment_id = wp_insert_attachment(
        array(
            'guid'           => $sideload['url'],
            'post_mime_type' => $sideload['type'],
            'post_title'     => basename( $sideload['file'] ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ),
        $sideload['file']
    );

    if(is_wp_error($attachment_id) || !$attachment_id) {
        return false;
    }

    // update medatata, regenerate image sizes
    require_once ABSPATH . 'wp-admin/includes/image.php';

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata($attachment_id, $sideload['file'])
    );

    if(!empty($post_id)) {
        set_post_thumbnail($post_id, $attachment_id);
    }

    return $attachment_id;
}
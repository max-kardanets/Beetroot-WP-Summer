<?php
/*
 * Template Name: Chat page
 * Type: page
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
    wp_body_open();

    echo do_shortcode('[chat]');

    wp_footer();
?>
</body>
</html>
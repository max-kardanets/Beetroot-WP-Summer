<?php
global $wp_query;

get_header();
?>

<div class="characters_page">
    <div class="characters_list">
<?php
if(!empty($wp_query->posts)) {
    foreach($wp_query->posts as $post) {
        get_template_part('template-parts/character', 'item', $post);
    }
}
?>
    </div>
    <?php the_posts_pagination(); ?>
</div>

<?php
get_footer();

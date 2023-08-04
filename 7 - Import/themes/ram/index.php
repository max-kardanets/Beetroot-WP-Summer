<?php
get_header();

echo '<h1>'. get_the_title() .'</h1>';

the_content();

get_footer();
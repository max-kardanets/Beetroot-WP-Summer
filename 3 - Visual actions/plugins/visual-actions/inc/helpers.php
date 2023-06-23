
<?php
function va_check_blacklist($string) {
    $rules = [
        '*_block_registration',
        'wp_register_sidebar_widget'
    ];

    $regex_patterns = [];

    foreach($rules as $rule) {
        $regex_patterns[] = str_replace(
            ['\*', '\?'], // wildcard chars
            ['.+', '.'],  // regex chars
            preg_quote($rule)
        );
    }

    $regex = '/^(' . implode('|', $regex_patterns) . ')$/is';

    return (bool) preg_match($regex, $string);
}
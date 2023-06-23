<?php
/**
 * Enqueue CSS/JS
 */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('va', VA_ROOT_URI .'/assets/va.css');
    wp_enqueue_script('va', VA_ROOT_URI .'/assets/va.js', ['jquery'], null, true);
});

/**
 * Display action block
 */
function va_display_action($hook_name) {
    if(!did_action($hook_name)) {
        return;
    }

    if(va_check_blacklist($hook_name)) {
        return;
    }

    global $wp_filter;

    // Count hooked functions
    $functions_count = 0;

    $html_table_func = null;
    if(!empty($wp_filter[$hook_name]->callbacks)) {
        foreach($wp_filter[$hook_name]->callbacks as $priority => $functions) {
            if(!empty($functions)) {
                foreach($functions as $function_name => $function_data) {
                    $functions_count++;

                    $html_table_func .= <<<HTML
<tr>
    <td>{$priority}</td>
    <td>{$function_name}</td>
</tr>
HTML;
                }
            }
        }
    }

    // Set active color for actions with functions
    $css_has_functions = ($functions_count > 0) ? 'has_funtions' : null;

    // Functions table
    $html_functions_table = null;

    if($functions_count > 0) {
        $html_functions_table = <<<HTML
<div class="va_action_functions">
    <table>
        <tr>
            <th>Priority</th>
            <th>Function name</th>
        </tr>
        {$html_table_func}
    </table>
</div>
HTML;

    }

    $html_action = <<<HTML
<div class="va_action_item {$css_has_functions}" style="display: none;">
    <div class="va_action_inner">
        <div class="va_action_name">
            {$hook_name}
            <div class="va_functions_count">{$functions_count}</div>
        </div>
        {$html_functions_table}
    </div>
</div>
HTML;

    echo $html_action;
}
add_action('all', 'va_display_action');

/**
 * Display plugin settings
 */
$html_settings = <<<HTML
<div class="va_settings">
<label><input type="checkbox" name="show_actions"> Show actions</label>
<label><input type="checkbox" name="show_active_actions"> Show active actions</label>
<label><input type="checkbox" name="show_templates"> Show templates</label>
</div>
HTML;

echo $html_settings;


/**
 * Show template file
 */
$main_tamplate_shown = false;
add_action('wp_before_load_template', function($template_name) {
    global $main_tamplate_shown, $template;

    if($main_tamplate_shown !== true) {
        $main_tamplate_shown = true;
        echo '<div class="va_template_item">'. $template .'</div>';
    }

    echo '<div class="va_template_item">'. $template_name .'</div>';
});

add_action('woocommerce_before_template_part', function($template) {
    echo '<div class="va_template_item" style="display: none;">'. $template .'</div>';
});

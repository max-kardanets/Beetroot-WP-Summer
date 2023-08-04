<?php
function import_page() {
    global $import_obj;

    wp_enqueue_style('import');
    wp_enqueue_script('import');

    // Import entities
    $import_entities = $import_obj->get_entities();

    $html_import_entities = null;
    if(!empty($import_entities)) {
        foreach($import_entities as $entity_slug => $entity_name) {
            $html_import_entities .= '<option value="'. $entity_slug .'">'. $entity_name .'</option>';
        }
    }

    // Import types
    $import_types = $import_obj->get_types();

    $html_import_types = null;
    if(!empty($import_types)) {
        foreach($import_types as $type_slug => $type_name) {
            $html_import_types .= '<option value="'. $type_slug .'">'. $type_name .'</option>';
        }
    }

    // Errors
    $html_errors = null;
    if(!empty($import_obj->log)) {
        foreach($import_obj->log as $item) {
            $error_message = $item['message'];

            $html_errors .= '<div>'. $error_message .'</div>';
        }

        $html_errors = '<div class="form_errors">'. $html_errors .'</div>';
    }

    // Page
    $html = <<<HTML
<div class="wrap import_page">
    <h1>Import</h1>
    <div class="import_top">
        <div class="import_form">
            <form>
                <div class="form_inner">
                    {$html_errors}
                    <div class="form_items">
                        <div class="form_item entity">
                            <div class="label">Entity:</div>
                            <select required>
                                <option value="" disabled selected>--- Select ---</option>
                                {$html_import_entities}
                            </select>
                        </div>
                        <div class="form_item type">
                            <div class="label">Import:</div>
                            <select required>
                                <option value="" disabled selected>--- Select ---</option>
                                <option value="all">All</option>
                                {$html_import_types}
                            </select>
                        </div>
                        <div class="form_item rewrite">
                            <div class="label">Rewrite existing values:</div>
                            <select required>
                                <option value="" disabled selected>--- Select ---</option>
                                <option>yes</option>
                                <option>no</option>
                            </select>
                        </div>
                    </div>
                    <div class="form_submit">
                        <input type="submit" value="Start Import">
                    </div>
                </div>
            </form>
        </div>
        <div class="import_status">Import status: <strong>in progress</strong></div>
        <div class="import_bar"><div><span>0</span>%</div></div>
    </div>
    <div class="import_log">
        <div class="log_inner"></div>
    </div>
</div>
HTML;

    echo $html;
}

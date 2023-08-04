<?php
function ajax_import() {
    global $import_obj;

    $entity = (!empty($_POST['entity'])) ? $_POST['entity'] : null;
    $type = (!empty($_POST['type'])) ? $_POST['type'] : null;
    $rewrite = (!empty($_POST['rewrite']) && ($_POST['rewrite'] == '1')) ? true : false;
    $data = (!empty($_POST['data'])) ? $_POST['data'] : null;

    if(empty($entity) || empty($type)) {
        die();
    }

    $import_obj->prepare_data([
        'entity' => $entity,
        'type' => $type,
        'rewrite' => $rewrite,
        'data' => $data,
    ]);

    $import_obj->run();

    die();
}
add_action('wp_ajax_import', 'ajax_import');
add_action('wp_ajax_nopriv_import', 'ajax_import');

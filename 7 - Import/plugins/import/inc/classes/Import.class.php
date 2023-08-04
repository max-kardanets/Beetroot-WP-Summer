<?php
class Import {
    // Settings
    protected $batch_size;

    // System
    public $log = [];
    protected $db;
    protected $pref;
    protected $entity;
    protected $type;
    protected $rewrite;
    protected $data;

    public function __construct() {
        // Settings
        $this->batch_size = 5;

        // System
        global $wpdb;
        $this->db = $wpdb;
        $this->pref = $this->db->prefix;
    }

    public function get_entities() {
        if(!$this->method_overrided(__FUNCTION__)) {
            return false;
        }

        $entities = [
            'news' => 'News',
        ];

        return $entities;
    }

    public function get_types() {
        if(!$this->method_overrided(__FUNCTION__)) {
            return false;
        }

        $import_types = [
            'posts' => 'Posts',
            'test' => 'Test',
        ];

        return $import_types;
    }

    public function prepare_data($data = []) {
        if(!empty($data['entity'])) {
            $this->entity = $data['entity'];
        }

        if(!empty($data['type'])) {
            $this->type = $data['type'];
        }

        if(!empty($data['rewrite'])) {
            $this->rewrite = $data['rewrite'];
        }

        if(!empty($data['data'])) {
            $this->data = $data['data'];
        }
    }

    protected function method_overrided($method_name) {
        $child_class = get_class($this);

        if($child_class !== __CLASS__) {
            $this->error_log('<span>Method <strong>'. $method_name .'()</strong> not found</span>', 'Override this method in your class');

            return false;
        } else {
            return true;
        }
    }

    protected function prepare_api_data() {
        if(!$this->method_overrided(__FUNCTION__)) {
            return;
        }

        if(empty($this->data['stats']['total'])) {
            $this->data['stats']['total'] = 120;
        }

        $words = [
            'Lorem', 'Ipsum', 'Dolor', 'Sit', 'Amet', 'Consectetur',
            'Adipiscing', 'Elit', 'Sed', 'Do', 'Eiusmod', 'Tempor',
            'Incididunt', 'Ut', 'Labore', 'Et', 'Dolore', 'Magna',
            'Aliqua', 'Ut', 'Enim', 'Ad', 'Minim', 'Veniam', 'Quis',
            'Nostrud', 'Exercitation', 'Ullamco', 'Laboris', 'Nisi',
            'Ut', 'Aliquip', 'Ex', 'Ea', 'Commodo', 'Consequat'
        ];

        $news_data = [];
        for($i = 0; $i < $this->batch_size; $i++) {
            $title = '';
            $num_words = rand(3, 6);
            for ($j = 0; $j < $num_words; $j++) {
                $random_word = $words[array_rand($words)];
                $title .= ucfirst($random_word) . ' ';
            }
            $news_data[$i + 1] = trim($title);
        }

        $this->api_data = $news_data;
    }

    protected function import_news_posts() {
        foreach($this->api_data as $news_title) {
            $this->data['stats']['processed']++;

            if($this->data['rewrite']) {
                $this->add_log('Post "'. $news_title .'" <span>created</span>');
            } else {
                if(rand(0,1)) {
                    $this->add_log('Post "'. $news_title .'" <span>skipped</span>', 'Post already exists');
                } else {
                    $this->add_log('Post "'. $news_title .'" <span>created</span>');
                }
            }
        }

        if($this->data['stats']['processed'] >= $this->data['stats']['total']) {
            $this->data['status'] = 'done';
        }
    }

    public function __call($name, $arguments) {
        switch($name) {
            case 'import_'. $this->entity .'_'. $this->type:
                $import_types = $this->get_types();

                if(!empty($import_types)) {
                    foreach($import_types as $type_slug => $type_name) {
                        $import_method = 'import_'. $this->entity .'_'. $type_slug;

                        if(method_exists($this, $import_method)) {
                            call_user_func([$this, $import_method]);
                        } else {
                            $this->error_log('<span>Method <strong>'. $import_method .'()</strong> not found</span>', 'Override this method in your class');
                        }
                    }
                }

                break;
        }
    }

    protected function add_log($message, $reason = false) {
        $time = date('H:i:s');

        $css = (!empty($reason)) ? 'red' : 'green';

        $txt_reason = (!empty($reason)) ? ' | <strong>Reason:</strong> '. $reason : null;

        $this->log[] = [
            'message' => $message . $txt_reason,
            'time' => $time,
            'class' => $css
        ];
    }

    protected function response() {
        $response = [
            'log' => $this->log,
            'data' => $this->data,
        ];

        echo json_encode($response);
    }

    protected function error_log($message, $reason) {
        $this->add_log($message, $reason);
        $this->data['stats']['total'] = 1;
        $this->data['stats']['processed'] = 1;
        $this->data['status'] = 'error';
    }

    public function sync_post($post_data, $api_meta_field, $api_id) {
        if(empty($post_data['post_type'])) {
            return;
        }

        if(empty($post_data['post_author'])) {
            $post_data['post_author'] = 1;
        }

        $post_exists = false;

        $post_id = $this->db->get_var("
            SELECT p.ID
            FROM {$this->pref}posts as p
                LEFT JOIN {$this->pref}postmeta as m ON m.post_id
            WHERE
                p.post_type = '{$post_data['post_type']}' AND
                m.meta_key = '{$api_meta_field}' AND
                m.meta_value = '{$api_id}'
            LIMIT 1
        ");

        if(!empty($post_id)) {
            $post_exists = true;
            $post_data['ID'] = $post_id;
        }

        $message = 'Post "<strong>'. $post_data['post_title'] .'</strong>" ';

        if($post_exists) {
            $message .= '(ID: '. $post_id .') ';

            if($this->data['rewrite']) {
                wp_insert_post(wp_slash($post_data));

                if(!empty($post_id)) {
                    $this->add_log($message . '<span>updated</span>');
                }
            } else {
                $this->add_log($message .'<span>skipped</span>', 'Post already exists');
            }
        } else {
            $post_id = wp_insert_post(wp_slash($post_data));

            if(!empty($post_id)) {
                update_field($api_meta_field, $api_id, $post_id);
                $this->add_log($message .'(ID: '. $post_id .') <span>created</span>');
            }
        }
    }

    public function sync_post_image($post_id, $image_url) {
        $thumbnail_exists = has_post_thumbnail($post_id);

        if($thumbnail_exists) {
            $this->add_log('Image for Post ID: '. $post_id .' <span>skipped</span>', 'Thumbnail already exists');
        } else {
            $thumbnail_id = upload_and_attach_image($image_url, $post_id);

            if(!empty($thumbnail_id)) {
                $this->add_log('Image for Post ID: '. $post_id .' <span>created</span>');
            }
        }
    }

    protected function sync_post_term($post_id, $term_name, $taxonomy) {
        $existing_term = has_term($term_name, $taxonomy, $post_id);

        if(!empty($existing_term)) {
            if($this->data['rewrite']) {
                wp_set_post_terms($post_id, $term_name, $taxonomy);
                $this->add_log('<strong>Term "'. $term_name .'"</strong> for Post ID: '. $post_id .' <span>created</span>');
            } else {
                $this->add_log('<strong>Term "'. $term_name .'"</strong> for Post ID: '. $post_id .' <span>skipped</span>', 'Term already exists');
            }
        } else {
            wp_set_post_terms($post_id, $term_name, $taxonomy);
            $this->add_log('<strong>Term "'. $term_name .'"</strong> for Post ID: '. $post_id .' <span>created</span>');
        }
    }

    public function run() {
        $this->prepare_api_data();

        // Dynamic import method
        $import_method = 'import_'. $this->entity .'_'. $this->type;

        if($this->type == 'all') {
            call_user_func([$this, $import_method]);
        } else {
            if(method_exists($this, $import_method)) {
                call_user_func([$this, $import_method]);
            } else {
                $this->error_log('<span>Method <strong>'. $import_method .'()</strong> not found</span>', 'Override this method in your class');
            }
        }

        if(
            ($this->data['status'] = 'in_progress') &&
            ($this->data['stats']['processed'] >= $this->data['stats']['total'])
        ) {
            $this->data['status'] = 'done';
        }

        $this->response();
    }
}

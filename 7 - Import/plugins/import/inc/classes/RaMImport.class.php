<?php
class RaMImport extends Import {
    public function get_entities() {
        $entities = [
            'characters' => 'Characters',
        ];

        return $entities;
    }

    public function get_types() {
        $import_types = [
            'posts' => 'Posts',
            'thumbnails' => 'Thumbnails',
            'taxonomies' => 'Taxonomies',
            'post_meta' => 'Post meta',
        ];

        return $import_types;
    }

    protected function prepare_api_data() {
        $this->batch_size = 5;

        if(!empty($this->data['api_data'])) {
            return;
        }

        $api_data = ram_api_request('https://rickandmortyapi.com/api/character', 'get');

        if(empty($api_data)) {
            return;
        }

        $total = (!empty($api_data['info']['count'])) ? $api_data['info']['count'] : null;
        //$this->data['stats']['total'] = $total;
        $this->data['stats']['total'] = 20;

        $api_data = (!empty($api_data['results'])) ? $api_data['results'] : null;

        if(empty($api_data)) {
            return;
        }

        $this->data['api_data'] = $api_data;
    }

    protected function import_characters_posts() {
        $count = 0;
        foreach($this->data['api_data'] as $k => $api_item) {
            if($count >= $this->batch_size) {
                break;
            }

            $char_id = (!empty($api_item['id'])) ? $api_item['id'] : null;
            $char_name = (!empty($api_item['name'])) ? $api_item['name'] : null;

            if(empty($char_id) || empty($char_name)) {
                $this->data['stats']['processed']++;
                unset($this->data['api_data'][$k]);
                continue;
            }

            $this->sync_post([
                'post_type' => 'character',
                'post_title' => $char_name,
                'post_status' => 'publish'
            ], 'api_id', $char_id);

            //$this->add_log('<pre>'. print_r($char_name, true) .'</pre>');

            $this->data['stats']['processed']++;
            unset($this->data['api_data'][$k]);

            $count++;
        }
    }

    protected function import_characters_thumbnails() {
        $count = 0;
        foreach($this->data['api_data'] as $k => $api_item) {
            if($count >= $this->batch_size) {
                break;
            }

            $char_id = (!empty($api_item['id'])) ? $api_item['id'] : null;
            $char_image = (!empty($api_item['image'])) ? $api_item['image'] : null;

            if(empty($char_id) || empty($char_image)) {
                $this->data['stats']['processed']++;
                unset($this->data['api_data'][$k]);
                continue;
            }

            $char_post_id = $this->get_character_id($char_id);

            if(!empty($char_post_id)) {
                $this->sync_post_image($char_post_id, $char_image);
            }

            $this->data['stats']['processed']++;
            unset($this->data['api_data'][$k]);

            $count++;
        }
    }

    protected function import_characters_taxonomies() {
        $count = 0;
        foreach($this->data['api_data'] as $k => $api_item) {
            if($count >= $this->batch_size) {
                break;
            }

            $char_id = (!empty($api_item['id'])) ? $api_item['id'] : null;
            $char_post_id = $this->get_character_id($char_id);

            /*$this->add_log('Char ID - '. $char_id);
            $this->add_log('Char Post ID - '. $char_post_id);*/

            $char_status = (!empty($api_item['status'])) ? $api_item['status'] : null;
            if(!empty($char_status)) {
                $this->sync_post_term($char_post_id, $char_status, 'character_status');
            }

            $char_species = (!empty($api_item['species'])) ? $api_item['species'] : null;
            if(!empty($char_species)) {
                $this->sync_post_term($char_post_id, $char_species, 'character_species');
            }

            $char_type = (!empty($api_item['type'])) ? $api_item['type'] : null;
            if(!empty($char_type)) {
                $this->sync_post_term($char_post_id, $char_type, 'character_type');
            }

            $char_gender = (!empty($api_item['gender'])) ? $api_item['gender'] : null;
            if(!empty($char_gender)) {
                $this->sync_post_term($char_post_id, $char_gender, 'character_gender');
            }

            $this->data['stats']['processed']++;
            unset($this->data['api_data'][$k]);

            $count++;
        }
    }

    protected function get_character_id($api_id) {
        $api_id = intval($api_id);

        $char_post_id = $this->db->get_var("
            SELECT p.ID
            FROM {$this->pref}posts as p
                LEFT JOIN {$this->pref}postmeta as m ON m.post_id = p.ID AND m.meta_key = 'api_id'
            WHERE
                (p.post_type = 'character') AND
                (m.meta_value = '{$api_id}')
        ");

        return $char_post_id;
    }
}

<?php
class ChatHelper {
    private $crypto_key;
    private $db;
    private $pref;

    public function __construct() {
        global $wpdb;

        $this->db = $wpdb;
        $this->pref = $this->db->prefix;

        $this->crypto_key = '9C785BB9A73FDF6C280789F3862DD214531FFF78DBB18CA92E5FF0AD37667780';
    }

    public function encrypt_user_id($user_id) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($user_id, 'aes-256-cbc', $this->crypto_key, OPENSSL_RAW_DATA, $iv);
        $encoded_user_id = base64_encode($iv . $encrypted);
        return $encoded_user_id;
    }

    public function decrypt_user_id($encodedID) {
        $decoded = base64_decode($encodedID);
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decoded, 0, $iv_length);
        $encrypted = substr($decoded, $iv_length);
        $userID = openssl_decrypt($encrypted, 'aes-256-cbc', $this->crypto_key, OPENSSL_RAW_DATA, $iv);
        return $userID;
    }

    public function db_delete_stream_user($user_id) {
        $this->db->delete(
            $this->pref .'chat_users',
            [
                'user_id' => $user_id,
            ],
        );
    }

    public function db_init_stream_user($user_id, $ws_id) {
        // Delete stream user (if exists)
        $this->db_delete_stream_user($user_id);

        // Add stream user
        $this->db->insert(
            $this->pref .'chat_users',
            [
                'user_id' => $user_id,
                'ws_id' => $ws_id,
            ],
        );
    }

    public function store_message($data) {
        $token = (!empty($data->token)) ? $data->token : null;
        $type = (!empty($data->type)) ? sanitize_text_field($data->type) : null;
        $message = (!empty($data->message)) ? esc_sql($data->message) : null;

        if(
            empty($token) ||
            empty($type) ||
            empty($message)
        ) {
            return;
        }

        $user_id = $this->decrypt_user_id($token);

        if(empty($user_id)) {
            return;
        }

        $target_type = null;

        switch($type) {
            case 'global':
                $target_type = 'global';
                break;
            case 'direct':
                $target_type = 'direct';
                break;
        }

        if(empty($target_type)) {
            return;
        }

        $this->db->insert(
            $this->pref .'chat_messages',
            [
                'target_type' => $target_type,
                'user_id' => $user_id,
                'message' => $message,
            ],
        );
    }
}
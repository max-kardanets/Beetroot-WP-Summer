<?php
namespace Chat;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        global $chat_helper;
        $this->helper = $chat_helper;

        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $json_msg = json_decode($msg);

        $token = $json_msg->token;
        $user_id = $this->helper->decrypt_user_id($token);

        // If the user ID is empty, it means a hack attempt
        if(empty($user_id)) {
            return;
        }

        // WS sender connection ID
        $sender_ws_id = $from->resourceId;

        // Type
        $type = $json_msg->type;

        if($type == 'auth') {
            $this->helper->db_init_stream_user($user_id, $sender_ws_id);
            return;
        }

        // User name
        $user_data = get_userdata($user_id);
        $json_msg->user_name = $user_data->display_name;
        $json_msg->user_color = number_to_color($user_id);

        // Store message
        $this->helper->store_message($json_msg);

        // Removing user token
        unset($json_msg->token);

        // Return updated message
        $msg = json_encode($json_msg);

        // Sending messages to clients
        foreach($this->clients as $client) {
            if($client !== $from) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
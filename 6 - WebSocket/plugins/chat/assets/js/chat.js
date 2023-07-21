jQuery(function ($) {
$(document).ready(function() {
    // WS
    let socket = new WebSocket('ws://chat.loc:8080');

    // WS new connection
    socket.onopen = function(e) {
        console.log('WebSocket connection established');

        let data = {
            type: 'auth',
            token: chat_vars.token,
        };

        socket.send(JSON.stringify(data));
    }

    // Listen WS stream
    socket.onmessage = function(e) {
        let data = JSON.parse(e.data);

        if(data) {
            chat_send_message(data);
        }
    };

    // WS send message
    $('.chat_box .chat_send_message form').on('submit', function() {
        let form = $(this)
        let message_elem = form.find('.message_text input');
        let message_val = message_elem.val();

        if(message_val.length > 0) {
            let data = {
                type: 'global',
                token: chat_vars.token,
                message: message_elem.val()
            };
            socket.send(JSON.stringify(data));

            // Show sent message in chat room
            chat_send_message(data, {'css': 'my'});

            // Clean up message field
            message_elem.val('');
        }

        return false;
    })

    function chat_send_message(message_data, data) {
        let messages_list = $('.chat_box .chat_messages');

        messages_list.append(chat_vars.templates.message);

        let message_elem = $('.message_item.new');
        message_elem.find('.message_text .text').text(message_data.message);

        if(message_data.user_name) {
            message_elem.find('.message_author').text(message_data.user_name);
        }

        if(message_data.user_color) {
            message_elem.find('.message_author').css('color', message_data.user_color);
        }

        if(data?.css) {
            message_elem.addClass(data.css);
        }

        let current_time = new Date();
        let hours = current_time.getHours();
        let minutes = current_time.getMinutes();
        let formatted_time = hours +':'+ minutes;
        message_elem.find('.message_time').text(formatted_time);

        message_elem.removeClass('new');

        $('.chat_box .messages_scroll').animate({scrollTop: messages_list.height()}, 500);
    }
});
});
jQuery(document).ready(function($) {
    let entity;
    let type;
    let rewrite;
    let step = 0;
    let import_data;

    $('.import_page form').on('submit', function() {
        let obj = $(this);
        entity = obj.find('.entity select').val();
        type = obj.find('.type select').val();
        rewrite = obj.find('.rewrite select').val();
        rewrite = (rewrite === 'yes') ? 1 : 0;

        $('.import_form').empty();
        $('.import_status, .import_bar').addClass('active');

        ajax_import();

        return false;
    });

    function ajax_import() {
        step++;

        $.ajax({
            url: ajaxurl + '?action=import',
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {
                entity: entity,
                type: type,
                rewrite: rewrite,
                data: import_data
            },
            success: function(data) {
                let bar = $('.import_bar');

                if(data.data) {
                    import_data = data.data;
                }

                // Items (prop)
                if(data.data.stats) {
                    items = data.data.stats;

                    // Update progress bar
                    let items_total = parseFloat(items.total);
                    let items_processed = parseFloat(items.processed);

                    let progress = items_processed / (items_total / 100);
                    progress = progress.toFixed(1);
                    if(progress % 1 === 0) {
                        progress = parseInt(progress);
                    }

                    bar.find('> div').css('width', progress +'%');
                    bar.find('span').text(progress);
                }

                // Log messages
                $('.import_log:not(.active)').addClass('active');
                let logger = $('.import_log .log_inner');

                // Step
                logger.append('<div class="step">Step #'+ step +'</div>');

                // Messages
                if(data.log) {
                    $.each(data.log, function(k, v) {
                        logger.append(
                            '<div class="log_item '+ v.class +'">\n' +
                                '<div class="log_message">'+ v.message +'</div>\n' +
                                '<div class="log_time">'+ v.time +'</div>\n' +
                            '</div>'
                        );
                    });

                    logger.animate({
                        scrollTop: logger.prop('scrollHeight')
                    }, 200);
                }

                switch(data.data.status) {
                    case 'in_progress':
                        ajax_import();
                        break;

                    case 'error':
                        bar.addClass('error');
                        $('.import_status strong').text('error');
                        break;

                    case 'done':
                        bar.addClass('done');
                        $('.import_status strong').text('completed');
                        break;

                }
            }
        });
    }
});
jQuery(document).ready(function($) {
    /**
     * Settings
     */
    apply_settings();

    $('.va_settings input').on('change', function() {
        let obj = $(this);
        let setting_name = obj.attr('name');
        let setting_value = obj.prop('checked');

        localStorage.setItem('va_'+ setting_name, setting_value);

        apply_settings();
    });

    function apply_settings() {
        let actions = localStorage.getItem('va_show_actions');
        actions = (actions == 'true') ? true : false;

        let active_actions = localStorage.getItem('va_show_active_actions');
        active_actions = (active_actions == 'true') ? true : false;

        if(actions) {
            $('.va_settings input[name="show_actions"]').prop('checked', true);
            $('.va_action_item').addClass('active');

            if(active_actions) {
                $('.va_settings input[name="show_active_actions"]').prop('checked', true);
                $('.va_action_item.active:not(.has_functions)').removeClass('active');
                $('.va_action_item.has_funtions').addClass('active');
            }
        } else {
            $('.va_action_item.active').removeClass('active');
        }

        let templates = localStorage.getItem('va_show_templates');
        templates = (templates == 'true') ? true : false;

        if(templates) {
            $('.va_settings input[name="show_templates"]').prop('checked', true);
            $('.va_template_item').addClass('active');
        } else {
            $('.va_template_item.active').removeClass('active');
        }
    }

    /**
     * On action click
     */
    $('.va_action_item.has_funtions').on('click', function() {
        let obj = $(this);
        let table = obj.find('.va_action_functions');

        if(table.is(':visible')) {
            table.hide();
        } else {
            table.show();
        }
    });
});
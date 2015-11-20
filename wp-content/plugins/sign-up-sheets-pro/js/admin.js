(function($) {

    $(document).ready(function(){

        postboxes.add_postbox_toggles(pagenow);

        if ($('.tasks LI').is('*')) {
            var last_css_id_array = $(".tasks LI").last().attr('id').split('-');
            var row_key = last_css_id_array[last_css_id_array.length - 1];
            $(".tasks").on('click', '.add-task-after', function() {
                // Find all comments within the given collection.
                var new_row_template = $(this).closest('.tasks-wrap').comments();

                // Update row_keys
                row_key++;
                new_element = $(this).closest("LI").after(new_row_template.get(0).nodeValue).next('LI');
                new_element.attr('id', new_element.attr('class').split(' ')[0] + '-' + row_key);
                new_element.find('INPUT').each(function(index) {
                    var new_element_name = $(this).attr('name');
                    var new_element_name_prefix = new_element_name.substr(0, new_element_name.indexOf('[') + 1);
                    var new_element_name_suffix = new_element_name.substr(new_element_name.indexOf(']'));
                    $(this).attr('name', new_element_name_prefix + row_key + new_element_name_suffix);
                });

                dls_sus_show_hide_task_dates();
                return false;
            });
            $(".tasks").on('click', '.remove-task', function() {
                if ($('.tasks LI').length == 1) {
                    $(this).prev().trigger('click');
                }
                $(this).parent("LI").remove();
                return false;
            });
        }

        // Task dates
        dls_sus_show_hide_task_dates();
        $('#sheetfield_use_task_dates').change(function(){
            dls_sus_show_hide_task_dates();
        });
        function dls_sus_show_hide_task_dates()
        {
            if ($('#sheetfield_use_task_dates').attr('checked') == 'checked') {
                $('.dls-sus-sheet-date-wrap').hide();
                $('.dls-sus-task-date').show();
                $('#sheet_date').val('');
            } else {
                $('.tasks .dls-sus-task-date INPUT').val('');
                $('.dls-sus-sheet-date-wrap').show();
                $('.dls-sus-task-date').hide();
            }
        }

        $(".dls_sus").on('focus', '.dls-sus-datepicker', function () {
            $(this).datepicker({});
        });

        $(".tasks").sortable({
            distance: 5,
            opacity: 0.6,
            cursor: 'move'
        });

        // Expand/Collapse all postboxes
        $('.dls-sus-expand-all-postbox').click(function(){
            if ($('.postbox').hasClass('closed')) {
                $('.postbox').removeClass('closed');
                dls_sus_toggle_postboxes();
            } else {
                $('.postbox').addClass('closed');
                dls_sus_toggle_postboxes();
            }
            return false;
        });

        $('.hndle').click(function(){
            dls_sus_toggle_postboxes();
        });

        function dls_sus_toggle_postboxes() {
            if ($('.postbox').hasClass('closed')) {
                $('.dls-sus-expand-all-postbox').text('+ Expand All');
            } else {
                $('.dls-sus-expand-all-postbox').text('- Collapse All');
            }
        }

    });

})(jQuery);
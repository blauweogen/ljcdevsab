jQuery(document).ready(function($) {

    var icon_field;

    // Custom popup box
    $(document).on('click', '.crum-icon-add', function(evt){

        icon_field = $(this).siblings('.iconname');

        $("#mnky-generator-wrap, #mnky-generator-overlay").show();
		$('.ui-dialog').hide();

        $('#mnky-generator-insert').on('click', function(event) {

            $('.mnky-generator-icon-select input:checked').addClass("mnky-generator-attr");
            $('.mnky-generator-icon-select input:not(:checked)').removeClass("mnky-generator-attr");


            var icon_name = $('.mnky-generator-icon-select input:checked').val();

            icon_field.val(icon_name);


            $(icon_field).parents('.metro-menu-item').find('.tile-icon').addClass(icon_name);


			$('.ui-dialog').show();
            $("#mnky-generator-wrap, #mnky-generator-overlay").hide();


            // Prevent default action
            event.preventDefault();

            return false;
        });

        return false;
    });

    $("#mnky-generator-close").click(function(){
        $("#mnky-generator-wrap, #mnky-generator-overlay").hide();
		$('.ui-dialog').show();
        return false;
    });

    // Icon pack select
    $('#mnky-generator-select-pack').change(function() {
		$('ul.ul-icon-list').hide();
		$('ul.'+ $(this).val()).show();
    });


});
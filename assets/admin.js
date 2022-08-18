"use strict";
/**
 * @package   DPG_WP_EventApi
 */
jQuery(document).ready(function ($) {
    $('select#eventapi_event_id,select#eventapi_edition_id').change( function () {
        let form =$(this).parents('form');
        form.find('input[type=submit]').click();
    });

    let custom_uploader;

    $('#event_api_default_image_button').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Kies een afbeelding',
            button: {
                text: 'Kies Afbeelding'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            let attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#event_api_default_image').val(attachment.url);
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
});
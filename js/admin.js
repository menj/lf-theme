/**
 * Admin JavaScript for Meta Boxes
 * Langgam Fikir WordPress Theme
 */

(function($) {
    'use strict';
    
    /**
     * Initialize About Page Logo Upload
     */
    function initLogoUploader() {
        var mediaUploader;
        
        // Upload logo button click handler
        $('.upload-logo-button').on('click', function(e) {
            e.preventDefault();
            
            // If the uploader object already exists, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Choose Logo',
                button: {
                    text: 'Use this logo'
                },
                multiple: false
            });
            
            // When an image is selected, run the callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#about_logo_id').val(attachment.id);
                $('.about-logo-preview img').attr('src', attachment.url);
                $('.about-logo-preview').show();
                $('.remove-logo-button').show();
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove logo button click handler
        $('.remove-logo-button').on('click', function(e) {
            e.preventDefault();
            $('#about_logo_id').val('');
            $('.about-logo-preview').hide();
            $(this).hide();
        });
    }
    
    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        initLogoUploader();
    });
    
})(jQuery);

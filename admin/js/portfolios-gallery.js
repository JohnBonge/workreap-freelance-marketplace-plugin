jQuery(document).ready(function($) {
    var file_frame;
    var $gallery = $('#portfolio_gallery_container .portfolio_gallery');
    var $gallery_ids = $('#portfolio_gallery-input');

    $('.add_gallery_images').on('click', 'a', function(event) {
        event.preventDefault();

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add to gallery',
            },
            multiple: true
        });

        file_frame.on('select', function() {
            var selection = file_frame.state().get('selection');
            var attachment_ids = $gallery_ids.val();

            selection.map(function(attachment) {
                attachment = attachment.toJSON();

                if (attachment.id) {
                    attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;

                    $gallery.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment.url + '" /><a href="#" class="remove" title="Remove image">&times;</a></li>');
                }
            });
            console.log(attachment_ids);
            $gallery_ids.val(attachment_ids);
        });

        file_frame.open();
    });

    $gallery.on('click', 'a.remove', function() {
        $(this).closest('li.image').remove();

        var attachment_ids = '';

        $('#portfolio_gallery_container .portfolio_gallery li.image').css('cursor', 'default').each(function() {
            var attachment_id = $(this).attr('data-attachment_id');
            attachment_ids = attachment_ids ? attachment_ids + ',' + attachment_id : attachment_id;
        });

        $gallery_ids.val(attachment_ids);

        return false;
    });
});

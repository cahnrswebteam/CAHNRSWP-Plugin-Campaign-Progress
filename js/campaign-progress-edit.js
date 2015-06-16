jQuery(document).ready(function($) {


	var custom_uploader;

	$( '.campaign-progress-set-link' ).on( 'click', 'a', function(e) {

		e.preventDefault();

		var upload_link  = $(this),
				container    = upload_link.parents( '.upload-set-wrapper' ),
				upload_input = container.find( '.campaign-progress-upload' );

		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		custom_uploader.on( 'select', function() {

			var attachment   = custom_uploader.state().get( 'selection' ).first().toJSON(),
					remove_link  = container.find( '.campaign-progress-remove-link' ),
					class_prefix = upload_link.attr( 'class' );
					placeholder  = $( '.campaign-progress-preview-container' ).find( '.' + class_prefix + '-placeholder' );

			// Insert attachment id into hidden input.
			upload_input.val( attachment.id );

			// Show the uploaded image in the preview.
			placeholder.replaceWith( '<img src="' + attachment.url + '" class="' + class_prefix + '-preview" />' );
			
			// Display remove link.
			remove_link.show();

		});

		custom_uploader.open();

	});

	// Upload "Remove" handling.
	$( '.campaign-progress-remove-link' ).on( 'click', 'a', function(e) {

		e.preventDefault();

		var remove_link  = $(this),
				upload_input = remove_link.parents( '.upload-set-wrapper' ).find( '.campaign-progress-upload' ),
				class_prefix = remove_link.attr( 'class' ),
				preview      = $( '.campaign-progress-preview-container' ).find( '.' + class_prefix + '-preview' );

		// Clear the input value.
		upload_input.val('');

		// Hide remove link.
		remove_link.parent( 'span' ).hide();

		// Remove the "Remove" link.
		preview.replaceWith( '<span class="' + class_prefix + '-placeholder"></span>' );

	});


});
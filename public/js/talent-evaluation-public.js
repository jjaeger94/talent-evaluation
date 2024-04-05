(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {
		$('#add-job-form').submit(function(e) {
			e.preventDefault();
	
			// Formulardaten serialisieren
			var formData = $(this).serialize();
	
			// Ajax-Anfrage senden
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl, // Verwende die global definierte ajaxurl
				data: formData + '&action=add_job', // Daten und Aktion hinzufügen
				success: function(response) {
					// Antwort verarbeiten
					$('#form-message').html(response);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

		$('#candidate-form').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: your_script_vars.ajaxurl,
                data: formData + '&action=add_candidate',
                success: function (response) {
                    $('#message').html(response); // Anzeigen der Antwortmeldung
                    $('#candidate-form')[0].reset(); // Formular zurücksetzen
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
	})

})( jQuery );

(function( $ ) {
	'use strict';

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
				xhr: function(){
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            if(percent === 100){
                                console.log('Datei verarbeiten');
                            }else{
                                console.log('uploaded',percent);
                            }
                        }, true);
                    }
                    return xhr;
                },
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
			var formData = new FormData(this); // FormData-Objekt erstellen und das Formular übergeben
			formData.append('action', 'add_candidate');
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl,
				data: formData,
				processData: false, // Daten nicht verarbeiten (wichtig für FormData)
				contentType: false, // Inhaltstyp nicht festlegen (wichtig für FormData)
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

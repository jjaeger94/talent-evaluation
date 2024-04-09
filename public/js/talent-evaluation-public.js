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

			
		$('#add-files-button').click(function() {
				// Hier können Sie den gewünschten Code einfügen, um weitere Dateien hinzuzufügen
				// Z.B. öffnen Sie einen Dateiauswahldialog
				var fileInput = document.createElement('input');
				fileInput.type = 'file';
				fileInput.multiple = true; // Erlaubt das Hochladen mehrerer Dateien
				fileInput.click(); // Klicken Sie auf das Eingabefeld, um den Dateiauswahldialog zu öffnen
	
				// Event-Listener für den Dateiauswahldialog
				fileInput.addEventListener('change', function() {
					var files = fileInput.files;
					var formData = new FormData();
	
					// Fügen Sie die ausgewählten Dateien dem FormData-Objekt hinzu
					for (var i = 0; i < files.length; i++) {
						formData.append('files[]', files[i]);
					}

					// Extrahieren Sie die application_id aus dem URL-Parameter
					var urlParams = new URLSearchParams(window.location.search);
					var applicationId = urlParams.get('id');
			
					// Fügen Sie die application_id dem FormData-Objekt hinzu
					formData.append('application_id', applicationId);
					formData.append('action', 'add_files');
					$.ajax({
						type: 'POST',
						url: your_script_vars.ajaxurl,
						data: formData,
						processData: false, // Daten nicht verarbeiten (wichtig für FormData)
						contentType: false, // Inhaltstyp nicht festlegen (wichtig für FormData)
						success: function (response) {
							console.log(response); // Anzeigen der Antwortmeldung // Formular zurücksetzen
						},
						error: function (xhr, status, error) {
							console.error(xhr.responseText);
						}
					});
				});
		});
		
	})

})( jQuery );

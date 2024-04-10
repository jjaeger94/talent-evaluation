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
				fileInput.accept = 'application/pdf';
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
							location.reload();
						},
						error: function (xhr, status, error) {
							console.error(xhr.responseText);
						}
					});
				});
		});
		
		$('.review-btn').click(function() {
			// Extrahieren Sie die application_id aus dem URL-Parameter
			var comment;
			if($(this).data('comment')){
				comment = prompt("Bitte geben Sie einen Kommentar ein:");
			}
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id');
			var requestData = {
				action: 'change_state',
				application_id: applicationId,
				state: $(this).val(),
				comment: comment
			};
		
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl,
				data: requestData,
				dataType: 'json', // Hier können Sie den erwarteten Datenformat angeben
				success: function(response) {
					if (response.success) {
						// Hier können Sie weitere Aktionen ausführen, z.B. die Seite neu laden
						location.reload();
					} else {
						// Fehler bei der Aktualisierung
						alert('Fehler beim Starten der Bearbeitung');
					}
				},
				error: function(xhr, status, error) {
					// AJAX-Fehler
					console.error('AJAX-Fehler:', error);
				}
			});
		});

		$('#review-btn-start').click(function() {
			// Extrahieren Sie die application_id aus dem URL-Parameter
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id');
			var requestData = {
				action: 'start_review',
				application_id: applicationId,
				state: $(this).val()
			};
		
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl,
				data: requestData,
				dataType: 'json', // Hier können Sie den erwarteten Datenformat angeben
				success: function(response) {
					if (response.success) {
						// Hier können Sie weitere Aktionen ausführen, z.B. die Seite neu laden
						location.reload();
					} else {
						// Fehler bei der Aktualisierung
						alert('Fehler beim Starten der Bearbeitung');
					}
				},
				error: function(xhr, status, error) {
					// AJAX-Fehler
					console.error('AJAX-Fehler:', error);
				}
			});
		});		

		$('.set-review-btn').click(function() {
			var comment;
			if($(this).data('comment')){
				comment = prompt("Bitte geben Sie einen Kommentar ein:");
			}
			// Extrahieren Sie die application_id aus dem URL-Parameter
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id');
			var requestData = {
				action: 'set_review',
				application_id: applicationId,
				value: $(this).val(),
				text: $(this).text(),
				type: $(this).data('type'),
				comment: comment
			};
		
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl,
				data: requestData,
				dataType: 'json', // Hier können Sie den erwarteten Datenformat angeben
				success: function(response) {
					if (response.success) {
						// Hier können Sie weitere Aktionen ausführen, z.B. die Seite neu laden
						location.reload();
					} else {
						// Fehler bei der Aktualisierung
						alert('Fehler beim Starten der Bearbeitung');
					}
				},
				error: function(xhr, status, error) {
					// AJAX-Fehler
					console.error('AJAX-Fehler:', error);
				}
			});
		});	

		$('#classification').change(function() {
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id'); // Hier sollten Sie die Anwendungs-ID dynamisch erhalten
			var option = $('option:selected', this);
			var comment;
			if(option.data('comment')){
				comment = prompt("Bitte geben Sie einen Kommentar ein:");
			}
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl, // Die URL Ihrer PHP-Datei zum Speichern der Einordnung
				data: {
					action: 'set_classification', 
					value: option.val(), 
					application_id: applicationId,
					text: option.text(),
					comment: comment
				},
				success: function(response) {
					// Erfolgreich gespeichert
					console.log('Einordnung erfolgreich gespeichert');
					location.reload();
				},
				error: function(xhr, status, error) {
					// Fehler beim Speichern
					console.error('Fehler beim Speichern der Einordnung:', error);
				}
			});
		});
		
	})

})( jQuery );

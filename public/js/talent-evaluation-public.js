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
					$('#add-job-form')[0].reset();
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

		$('#application-form').submit(function (event) {
			event.preventDefault();
			var formData = new FormData(this); // FormData-Objekt erstellen und das Formular übergeben
			formData.append('action', 'add_application');
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl,
				data: formData,
				processData: false, // Daten nicht verarbeiten (wichtig für FormData)
				contentType: false, // Inhaltstyp nicht festlegen (wichtig für FormData)
				success: function (response) {
					$('#message').html(response); // Anzeigen der Antwortmeldung
					$('#application-form')[0].reset(); // Formular zurücksetzen
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

		$('.job-state-btn').click(function() {
			// Extrahieren Sie die application_id aus dem URL-Parameter
			var urlParams = new URLSearchParams(window.location.search);
			var jobId = urlParams.get('id');
			var requestData = {
				action: 'change_state',
				job_id: jobId,
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
		
		$('#load-backlog-button').click(function() {
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id'); // Hier sollten Sie die Anwendungs-ID dynamisch erhalten
			$.ajax({
				url: your_script_vars.ajaxurl,
				type: 'POST',
				data: {
					action: 'get_backlog',  
					application_id: applicationId
				},
				success: function(response) {
					// Fügen Sie den geladenen Inhalt dem Container hinzu
					$('#backlog-container').html(response);
				},
				error: function(xhr, status, error) {
					// Behandeln Sie Fehler hier
					console.error('Fehler beim Laden des Backlogs:', error);
				}
			});
		});

		$('#edit-user-data-form').submit(function(e) {
            e.preventDefault(); // Verhindert das Standard-Formular-Verhalten
            
            var formData = $(this).serialize(); // Serialisiert die Formulardaten
            
            $.ajax({
                url: your_script_vars.ajaxurl,
                type: 'POST',
                data: formData + '&action=save_user_data', // Fügt die Aktion hinzu
                success: function(response) {
                    // Erfolgsfall: Weiterleitung oder Anzeige einer Erfolgsmeldung
                    console.log(response);
					$('#message').html(response);
                },
                error: function(xhr, status, error) {
                    // Fehlerfall: Anzeige einer Fehlermeldung
                    console.error('Fehler beim Speichern der Benutzerdaten:', error);
                }
            });
        });

		$('[data-toggle="popover"]').popover();

		// Event-Listener hinzufügen, um das Popover zu schließen, wenn außerhalb geklickt wird
		$(document).on('click', function (e) {
			// Überprüfen, ob das geklickte Element ein Popover ist oder innerhalb des Popovers liegt
			if (!$(e.target).closest('.popover').length && !$(e.target).closest('[data-toggle="popover"]').length) {
				// Schließen Sie alle geöffneten Popovers
				$('[data-toggle="popover"]').popover('hide');
			}
		});

		
	})

})( jQuery );

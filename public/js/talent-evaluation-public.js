(function( $ ) {
	'use strict';

	$(function() {
		// Klick-Ereignis für den "Löschen"-Button hinzufügen
		$('#delete-question').on('click', function() {
			if (confirm('Möchten Sie diese Frage wirklich löschen?')) {
				// Frage-ID aus dem Datenattribut abrufen
				var urlParams = new URLSearchParams(window.location.search);
			
				if(urlParams.has('qid')){
					var questionId = urlParams.get('qid');
					$.ajax({
						type: 'POST',
						url: your_script_vars.ajaxurl,
						data: {
							action: 'delete_question',
							question_id: questionId
						},
						success: function(response) {
							// Erfolgsfall: Verarbeite die Antwort
							console.log(response);
							$('#form-message').html(response);
							// Aktualisiere die Seite oder führe weitere Aktionen aus
						},
						error: function(xhr, status, error) {
							// Fehlerfall: Behandele den Fehler
							console.error(error);
						}
					});
				}else{
					console.error('id nicht gefunden')
				}
			}
		});


		$('#edit-question-form').on('submit', function(e) {
			e.preventDefault(); // Verhindert das Standardverhalten des Formulars (Neuladen der Seite)
			
			var urlParams = new URLSearchParams(window.location.search);
			var formData = $(this).serialize(); // Manuelle Serialisierung des Formulars
			
			// Hinzufügen von zusätzlichen Daten zum FormData
			formData += '&action=edit_question';
			var testId = urlParams.get('tid');
			formData += '&test_id=' + testId;
			if(urlParams.has('qid')){
				var questionId = urlParams.get('qid');
				formData += '&question_id=' + questionId;
			}
			
			// AJAX-Anfrage senden
			$.ajax({
				type: 'POST', // Verwendung von POST-Methode
				url: your_script_vars.ajaxurl, // Ziel-URL für die AJAX-Anfrage
				data: formData, // Seriatisierung der Formulardaten
				success: function(response) {
					// Erfolgsfall: Verarbeite die Antwort
					console.log(response);
					$('#form-message').html(response);
				},
				error: function(xhr, status, error) {
					// Fehlerfall: Behandele den Fehler
					console.error(error);
				}
			});
		});
		

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
					$('#add-job-form')[0].reset();
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

		$('#create-test-form').submit(function(e) {
			e.preventDefault();
	
			// Formulardaten serialisieren
			var formData = $(this).serialize();
	
			// Ajax-Anfrage senden
			$.ajax({
				type: 'POST',
				url: your_script_vars.ajaxurl, // Verwende die global definierte ajaxurl
				data: formData + '&action=add_test', // Daten und Aktion hinzufügen
				success: function(response) {
					// Antwort verarbeiten
					$('#form-message').html(response);
					$('#create-test-form')[0].reset();
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

		$('#application-form').submit(function (e) {
			e.preventDefault();
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
					$('#message').html(response.data);
                },
                error: function(xhr, status, error) {
                    // Fehlerfall: Anzeige einer Fehlermeldung
                    console.error('Fehler beim Speichern der Benutzerdaten:', error);
                }
            });
        });

		$('#save-consent').click(function(e) {
			e.preventDefault();
			var urlParams = new URLSearchParams(window.location.search);
			var applicationId = urlParams.get('id');
			var key = urlParams.get('key');
			var pdf = generatePDF();
			var formData = new FormData($('#consent-form')[0]); // FormData-Objekt erstellen und das Formular übergeben
			formData.append('action', 'save_consent');
			formData.append('application_id', applicationId);
			formData.append('key', key);
			var pdf = generatePDF(); // Erstelle das PDF-Dokument
			formData.append('file', pdf, 'consent_' + Date.now() + '.pdf'); // Füge das Blob-Objekt als Datei hinzu
		
			$.ajax({
				url: your_script_vars.ajaxurl,
				method: 'POST',
				data: formData,
				contentType: false, // Wichtig für das Senden von Dateien
				processData: false, // Wichtig für das Senden von Dateien
				success: function(response) {
					console.log(response);
					$('#consent-container').html('Das wars schon, du kannst die Seite jetzt schließen.');
					// Hier können Sie weitere Aktionen nach dem Speichern auf dem Server durchführen
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
				}
			});
		});
		
		function generatePDF(){
			var doc = new jspdf.jsPDF();
			var yOffset = 20; // Startposition für das erste Formularfeld

			doc.text("Einverständniserklärung", 10, yOffset); // Überschrift einfügen

			yOffset += 20;

			var preConsentText = $('#pre-consent-text').text();
			var preConsentTextLines = doc.splitTextToSize(preConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
			doc.text(preConsentTextLines, 10, yOffset); // Text aus dem HTML-Element "consent-text" einfügen
			yOffset += 20;


		
			// Iteriere durch die Formularfelder
			$('#consent-form :input').each(function(index, element) {
				var elementType = $(element).attr('type');
				var elementValue = '';
		
				if (elementType === 'checkbox') {
					// Position und Größe der Checkbox festlegen
					var checkboxX = 10;
					var checkboxY = yOffset;
					var checkboxWidth = 5;
					var checkboxHeight = 5;
		
					// Überprüfen, ob die Checkbox angehakt ist
					var isChecked = $(element).is(':checked');
		
					// Checkbox zeichnen und Zustand basierend auf dem isChecked-Wert festlegen
					doc.rect(checkboxX, checkboxY, checkboxWidth, checkboxHeight);
					if (isChecked) {
						// Kreuz in die Checkbox einfügen, wenn sie nicht angehakt ist
						doc.line(checkboxX, checkboxY, checkboxX + checkboxWidth, checkboxY + checkboxHeight);
						doc.line(checkboxX, checkboxY + checkboxHeight, checkboxX + checkboxWidth, checkboxY);
					}
					
					doc.text($(element).next('label').text(), checkboxX + checkboxWidth + 2, checkboxY + 4); // Label für die Checkbox hinzufügen
					yOffset += 10; // Anpassung der Y-Position für das nächste Formularfeld
				} else {
					// Übernehme den Wert für andere Feldtypen
					elementValue = $(element).val();
					doc.text($(element).prev('label').text(), 10, yOffset + 4); // Label für das Eingabefeld hinzufügen
					doc.text(elementValue, 50, yOffset + 4); // Wert des Eingabefelds hinzufügen
					yOffset += 10; // Anpassung der Y-Position für das nächste Formularfeld
				}
			});

			yOffset += 10;

			var postConsentText = $('#post-consent-text').text();
			var postConsentTextLines = doc.splitTextToSize(postConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
			doc.text(postConsentTextLines, 10, yOffset);
			yOffset += 30;

			if($('#work-consent-text')[0]){
				var workConsentText = $('#work-consent-text').text();
				var workConsentTextLines = doc.splitTextToSize(workConsentText, 180); // Begrenzung der Breite auf 180 (angepasst nach Bedarf)
				doc.text(workConsentTextLines, 10, yOffset);
				yOffset += 30;
			}


			doc.text($('#date-consent-text').text(), 10, yOffset);
			yOffset += 10;	
		
			// Unterschrift hinzufügen
			var imgData = signaturePad.toDataURL();
			doc.addImage(imgData, 'PNG', 10, yOffset, 100, 40); // Position und Größe der Unterschrift anpassen
		
			// PDF speichern
			return doc.output('blob');
		}

		$('#clear-signature').click(function() {
            var canvas = $('#signature-pad')[0];
			var signaturePad = new SignaturePad(canvas);
			signaturePad.clear();
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

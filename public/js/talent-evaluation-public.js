(function( $ ) {
	'use strict';

	$(function() {

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

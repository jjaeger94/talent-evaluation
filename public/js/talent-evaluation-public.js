(function( $ ) {
	'use strict';

	$(function() {
		$('[data-toggle="popover"]').popover({
			content: function(popover) {
				return $(popover).data('content');
			}
		});

		$(window).bind('popstate',  function(event) {
			console.log('popstae', event);
			if($('.modal.fade.show')[0]){
				$('.modal.fade.show').modal('hide');
			}
		});

		// Event-Listener hinzufügen, um das Popover zu schließen, wenn außerhalb geklickt wird
		$(document).on('click', function (e) {
			// Überprüfen, ob das geklickte Element ein Popover ist oder innerhalb des Popovers liegt
			if (!$(e.target).closest('.popover').length && !$(e.target).closest('[data-toggle="popover"]').length) {
				// Schließen Sie alle geöffneten Popovers
				$('[data-toggle="popover"]').popover('hide');
			}
		});

		$('.collapse').on('show.bs.collapse', function(){
			// Collapse all sections except the one being shown
			$('.collapse').not(this).collapse('hide');
			// Add the ID of the collapse element to the URL
			var collapseId = $(this).attr('id');
			history.replaceState(null, null, '#' + collapseId);
		});

		// Check if there is a collapse ID in the URL
		var hash = window.location.hash;
		if (hash) {
			var collapseElement = $(hash);
			if (collapseElement.hasClass('collapse')) {
				collapseElement.collapse('show');
			}
		}

		// Update URL hash when a collapse element is hidden
		$('.collapse').on('hide.bs.collapse', function() {
			if (window.location.hash === '#' + $(this).attr('id')) {
				history.replaceState(null, null, ' ');
			}
		});

		setTimeout(function() {
			// Elementor "Umschalter" Accordion immer ZU:
			$('.elementor-tab-title').removeClass('elementor-active');
			$('.elementor-tab-content').css('display', 'none');
	
			// ULTIMATE Accordion immer ZU:
			$('.uael-accordion-title').removeClass('uael-title-active');
			$('.uael-accordion-content').css('display', 'none');
		}, 100);

		
	})

})( jQuery );
